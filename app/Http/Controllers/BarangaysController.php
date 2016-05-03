<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\BarangayCreateRequest;
use App\Http\Requests\BarangayUpdateRequest;
use App\Repositories\BarangayRepository;
use App\Validators\BarangayValidator;


class BarangaysController extends Controller
{

    /**
     * @var BarangayRepository
     */
    protected $repository;

    /**
     * @var BarangayValidator
     */
    protected $validator;


    public function __construct(BarangayRepository $repository, BarangayValidator $validator)
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
        $barangays = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $barangays,
            ]);
        }

        return view('barangays.index', compact('barangays'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('barangays.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  BarangayCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BarangayCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $barangay = $this->repository->create($request->all());

            $response = [
                'message' => 'Barangay created.',
                'data'    => $barangay->toArray(),
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
        $barangay = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $barangay,
            ]);
        }

        return view('barangays.show', compact('barangay'));
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

        $barangay = $this->repository->find($id);

        return view('barangays.edit', compact('barangay'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  BarangayUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(BarangayUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $barangay = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Barangay updated.',
                'data'    => $barangay->toArray(),
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
                'message' => 'Barangay deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Barangay deleted.');
    }
}
