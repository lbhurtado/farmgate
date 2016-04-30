<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ElectionResultCreateRequest;
use App\Http\Requests\ElectionResultUpdateRequest;
use App\Repositories\ElectionResultRepository;
use App\Validators\ElectionResultValidator;


class ElectionResultsController extends Controller
{

    /**
     * @var ElectionResultRepository
     */
    protected $repository;

    /**
     * @var ElectionResultValidator
     */
    protected $validator;


    public function __construct(ElectionResultRepository $repository, ElectionResultValidator $validator)
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
        $electionResults = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $electionResults,
            ]);
        }

        return view('electionResults.index', compact('electionResults'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('electionResults.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ElectionResultCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ElectionResultCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $electionResult = $this->repository->create($request->all());

            $response = [
                'message' => 'ElectionResult created.',
                'data'    => $electionResult->toArray(),
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
        $electionResult = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $electionResult,
            ]);
        }

        return view('electionResults.show', compact('electionResult'));
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

        $electionResult = $this->repository->find($id);

        return view('electionResults.edit', compact('electionResult'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ElectionResultUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ElectionResultUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $electionResult = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'ElectionResult updated.',
                'data'    => $electionResult->toArray(),
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
                'message' => 'ElectionResult deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ElectionResult deleted.');
    }
}
