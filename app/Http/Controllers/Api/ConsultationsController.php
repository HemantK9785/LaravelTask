<?php
namespace App\Http\Controllers\Api;
       
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Consultations;
use Validator;
use Carbon\Carbon;
use App\Http\Resources\ConsultationResource;
use Illuminate\Http\JsonResponse;

class ConsultationsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Consultations::all();        
        return $this->sendResponse(ConsultationResource::collection($consultations), 'Consultations retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):JsonResponse
    {       
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'phone_no' => 'required|numeric',
            'email' => 'required|email|unique:consultations',
            'consultation_schedule' => 'required',
            'specialty' => 'required',
        ]);
       
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input['user_id'] = auth()->user()->id;
        $input['status'] = 'Pending';
        $date = Carbon::parse($input['consultation_schedule']);
        $input['consultation_schedule']=$date->format('d-m-Y');
        $consultations = Consultations::create($input);
       
        return $this->sendResponse(new ConsultationResource($consultations), 'Consultations created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id):JsonResponse
    {
        $consultations = Consultations::find($id);      
        if (is_null($consultations)) {
            return $this->sendError('Consultations not found.');
        }       
        return $this->sendResponse(new ConsultationResource($consultations), 'Consultations retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consultations $consultations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id):JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'consultation_schedule' => 'required',
        ]);
       
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $consultations=Consultations::find($id);
        $date = Carbon::parse($input['consultation_schedule']);       
        $consultations->consultation_schedule = $date->format('d-m-Y');
        $consultations->user_id=auth()->user()->id;
        $consultations->save();
       
        return $this->sendResponse(new ConsultationResource($consultations), 'Consultations updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $res=Consultations::find($id)->delete();
        return $this->sendResponse([], 'Consultations deleted successfully.');
    }
}
