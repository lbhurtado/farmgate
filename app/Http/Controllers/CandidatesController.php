<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CandidateCreateRequest;
use App\Http\Requests\CandidateUpdateRequest;
use App\Repositories\CandidateRepository;
use App\Validators\CandidateValidator;


class CandidatesController extends Controller
{

    /**
     * @var CandidateRepository
     */
    protected $repository;

    /**
     * @var CandidateValidator
     */
    protected $validator;


    public function __construct(CandidateRepository $repository, CandidateValidator $validator)
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
        $candidates = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $candidates,
            ]);
        }

        return view('candidates.index', compact('candidates'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('candidates.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  CandidateCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CandidateCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $candidate = $this->repository->create($request->all());

            $response = [
                'message' => 'Candidate created.',
                'data'    => $candidate->toArray(),
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
        $candidate = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $candidate,
            ]);
        }

        return view('candidates.show', compact('candidate'));
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

        $candidate = $this->repository->find($id);

        return view('candidates.edit', compact('candidate'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  CandidateUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(CandidateUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $candidate = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Candidate updated.',
                'data'    => $candidate->toArray(),
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
                'message' => 'Candidate deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Candidate deleted.');
    }
}
