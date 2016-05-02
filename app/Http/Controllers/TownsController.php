<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TownCreateRequest;
use App\Http\Requests\TownUpdateRequest;
use App\Repositories\TownRepository;
use App\Validators\TownValidator;


class TownsController extends Controller
{

    /**
     * @var TownRepository
     */
    protected $repository;

    /**
     * @var TownValidator
     */
    protected $validator;


    public function __construct(TownRepository $repository, TownValidator $validator)
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
        $towns = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $towns,
            ]);
        }

        return view('towns.index', compact('towns'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('towns.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  TownCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TownCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $town = $this->repository->create($request->all());

            $response = [
                'message' => 'Town created.',
                'data'    => $town->toArray(),
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
        $town = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $town,
            ]);
        }

        return view('towns.show', compact('town'));
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

        $town = $this->repository->find($id);

        return view('towns.edit', compact('town'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  TownUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(TownUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $town = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Town updated.',
                'data'    => $town->toArray(),
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
                'message' => 'Town deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Town deleted.');
    }
}
