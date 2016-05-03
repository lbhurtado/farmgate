<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PollingPlaceCreateRequest;
use App\Http\Requests\PollingPlaceUpdateRequest;
use App\Repositories\PollingPlaceRepository;
use App\Validators\PollingPlaceValidator;


class PollingPlacesController extends Controller
{

    /**
     * @var PollingPlaceRepository
     */
    protected $repository;

    /**
     * @var PollingPlaceValidator
     */
    protected $validator;


    public function __construct(PollingPlaceRepository $repository, PollingPlaceValidator $validator)
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
        $pollingPlaces = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $pollingPlaces,
            ]);
        }

        return view('pollingPlaces.index', compact('pollingPlaces'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('pollingPlaces.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  PollingPlaceCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PollingPlaceCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $pollingPlace = $this->repository->create($request->all());

            $response = [
                'message' => 'PollingPlace created.',
                'data'    => $pollingPlace->toArray(),
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
        $pollingPlace = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $pollingPlace,
            ]);
        }

        return view('pollingPlaces.show', compact('pollingPlace'));
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

        $pollingPlace = $this->repository->find($id);

        return view('pollingPlaces.edit', compact('pollingPlace'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PollingPlaceUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(PollingPlaceUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $pollingPlace = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'PollingPlace updated.',
                'data'    => $pollingPlace->toArray(),
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
                'message' => 'PollingPlace deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PollingPlace deleted.');
    }
}
