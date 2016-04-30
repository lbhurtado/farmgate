<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ClusterCreateRequest;
use App\Http\Requests\ClusterUpdateRequest;
use App\Repositories\ClusterRepository;
use App\Validators\ClusterValidator;


class ClustersController extends Controller
{

    /**
     * @var ClusterRepository
     */
    protected $repository;

    /**
     * @var ClusterValidator
     */
    protected $validator;


    public function __construct(ClusterRepository $repository, ClusterValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $clusters = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $clusters,
            ]);
        }

        return view('clusters.index', compact('clusters'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('clusters.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ClusterCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ClusterCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $cluster = $this->repository->create($request->all());

            $response = [
                'message' => 'Cluster created.',
                'data'    => $cluster->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessage()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cluster = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $cluster,
            ]);
        }

        return view('clusters.show', compact('cluster'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $cluster = $this->repository->find($id);

        return view('clusters.edit', compact('cluster'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ClusterUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ClusterUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $cluster = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Cluster updated.',
                'data'    => $cluster->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessage()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Cluster deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Cluster deleted.');
    }
}
