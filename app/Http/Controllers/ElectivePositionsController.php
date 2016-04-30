<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ElectivePositionCreateRequest;
use App\Http\Requests\ElectivePositionUpdateRequest;
use App\Repositories\ElectivePositionRepository;
use App\Validators\ElectivePositionValidator;


class ElectivePositionsController extends Controller
{

    /**
     * @var ElectivePositionRepository
     */
    protected $repository;

    /**
     * @var ElectivePositionValidator
     */
    protected $validator;


    public function __construct(ElectivePositionRepository $repository, ElectivePositionValidator $validator)
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
        $electivePositions = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $electivePositions,
            ]);
        }

        return view('electivePositions.index', compact('electivePositions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('electivePositions.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ElectivePositionCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ElectivePositionCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $electivePosition = $this->repository->create($request->all());

            $response = [
                'message' => 'ElectivePosition created.',
                'data'    => $electivePosition->toArray(),
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
        $electivePosition = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $electivePosition,
            ]);
        }

        return view('electivePositions.show', compact('electivePosition'));
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

        $electivePosition = $this->repository->find($id);

        return view('electivePositions.edit', compact('electivePosition'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ElectivePositionUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ElectivePositionUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $electivePosition = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'ElectivePosition updated.',
                'data'    => $electivePosition->toArray(),
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
                'message' => 'ElectivePosition deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ElectivePosition deleted.');
    }
}
