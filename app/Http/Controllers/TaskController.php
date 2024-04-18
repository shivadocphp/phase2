<?php

namespace App\Http\Controllers;

use App\Models\Client_requirement;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getTask(Request $request, $type)
    {
        // print_r($request->all());exit();
        if ($request->ajax()) {
            $tasks = Task::join('employee_personal_details', 'employee_personal_details.id', 'tasks.employee_id')
                    ->select('tasks.id', 'tasks.allocation_id', 'tasks.requirement_id', 'tasks.allocated_no', 
                        'tasks.employee_id', 'employee_personal_details.emp_code', 'employee_personal_details.firstname', 
                        'employee_personal_details.middlename', 'employee_personal_details.lastname')
                        ->where('tasks.deleted_by', NULL)
                        ->where('tasks.allocation_id', $request->list_task_id)   //added
                        ->get();

            $each_task = new Collection();
            foreach ($tasks as $key => $value) {
                $tenure = '-';
                $user = User::find(Auth::user()->id);
                $require = Client_requirement::join('designations', 'designations.id', 'client_requirements.position')
                    ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                    ->join('states', 'states.id', 'client_addresses.state_id')
                    ->join('cities', 'cities.id', 'client_addresses.city_id')
                    ->select('client_requirements.id', 'designations.designation', 'client_requirements.total_position',
                        'states.state', 'cities.city', 'client_addresses.address', 'client_requirements.requirement_status')
                    ->where('client_requirements.id', $value->requirement_id)
                    ->first();
                // print_r($require);

                $requirement = $require->designation . "( " . $require->total_position . " )" . ", " . $require->address . "," . $require->city . "," . $require->state;
                $employee = $value->emp_code . " - " . $value->firstname . " " . $value->middlename . " " . $value->lastname;

                $action = '<a href="" title="Edit" class="edit" data-id="' . $value->id . '"data-requirement="' . $requirement . '"
                                          data-employee="' . $employee . '" data-allocated_no="' . $value->allocated_no . '"
                                          data-allocation_id="' . $value->allocation_id . '" data-total_position="' . $require->total_position . '"
                                          data-toggle="modal" data-target="#edit" >
                                          <i class="fa fa-edit" style="color: green"></i></a>
                                          &nbsp;&nbsp;
                                          <a href="' . route('destroy.taskallocation', $value->id) . '" title="Delete">
                                          <i class="fa fa-trash" style="color: red"></i></a>';

                if ($type == "show") {
                    $each_task->push([

                        'position' => $require->designation,
                        'vacancy' => $require->total_position,
                        'location' => $require->address . "," . $require->city . "," . $require->state,
                        'allocated_to' => $value->emp_code . " - " . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                        'allocated_no' => $value->allocated_no,
                        'status' => $require->requirement_status,
                    ]);

                } else if ($type == "edit") {
                    $each_task->push([

                        'position' => $require->designation,
                        'vacancy' => $require->total_position,
                        'location' => $require->address . "," . $require->city . "," . $require->state,
                        'allocated_to' => $value->emp_code . " - " . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                        'allocated_no' => $value->allocated_no,
                        'status' => $require->requirement_status,
                        'action' => $action,
                    ]);
                }
            }
            // exit();
            return DataTables::of($each_task)->addIndexColumn()->make(true);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type)
    {
        // print_r($request->all());exit();
        
        // $input = $request->except(['_token',]);
        
        $require = $request->requirement_id;
        $employee = $request->employee_id;
        $id = $request->allocation_id;

        $input['added_by'] = Auth::user()->id;
        $input['created_at'] = Carbon::now();
        // One to one
        $i = 0;
        $total = 0;
        if ($type == 1) {
            $total_vacancy = Client_requirement::where('id', $require)->get();
            foreach ($total_vacancy as $tv) {
                $total = $tv->total_position;
            }
            $input['allocated_no'] = $request->allocated_no;
            $input['allocation_id'] = $request->allocation_id;
            $input['requirement_id'] = $require;
            $input['employee_id'] = $employee;
            $total_allocated = Task::where('requirement_id', $require)
            ->where('deleted_at', NULL)
            ->sum('allocated_no');

            $allocated_no = $request->allocated_no;
            if ($total >= $total_allocated) {
                $remaining = $total - $total_allocated;
                // echo $remaining .'<br>'. $allocated_no;exit();
                if ($remaining >= $allocated_no) {

                    // to check the task already exists for particular allocation, employe & requirement 
                    $task_exists_check = Task::where('allocation_id', $id)
                    ->where('requirement_id', $require)
                    ->where('employee_id', $employee)->get();
                    if (!$task_exists_check->isEmpty()) {
                        return redirect()->route('list_task.allocation', $id)->with('error', 'Allocation  already exists for the employee! Please try to update the with allocated no.');
                    }
                    
                    $i = Task::insertGetId($input);
                    if ($i > 0) {
                        return redirect()->route('list_task.allocation', $id)->with('success', 'Allocated successfully.');
                    }
                } else {
                    return redirect()->route('allocate_task.allocation', $id)->with('error', 'Allocated  no is greater than remaining vacancy to be allocated.');
                }
            }
        } // Many to one
        else if ($type == 2) {
                // print_r($request->all());exit();
            $loop=0;
            foreach ($require as $k) {
                // print_r($k);
                $total=0;
                $total_vacancy = Client_requirement::where('id', $k)->get();
                foreach ($total_vacancy as $tv) {
                    $total = $tv->total_position;
                }
                $total_allocated = Task::where('requirement_id', $k)
                    ->where('deleted_at', NULL)
                    ->sum('allocated_no');
                // print_r($total_allocated);exit();
                $allocated_no = $request->allocated_no;
                if ($total >= $total_allocated) {
                    $remaining = $total - $total_allocated;
                    if ($remaining >= $allocated_no) {

                        // to check the task already exists for particular allocation, employe & requirement 
                        $task_exists_check = Task::where('allocation_id', $request->allocation_id)
                        ->where('requirement_id', $k)
                        ->where('employee_id', $employee)->get();
                        // print_r($task_exists_check);exit();
                        if ($task_exists_check->isEmpty()) {
                            // DB::beginTransaction();
                            $input['requirement_id'] = $k;
                            $input['allocated_no'] = $request->allocated_no;
                            $input['allocation_id'] = $request->allocation_id;
                            $input['employee_id'] = $employee;
                            $i = Task::insertGetId($input);
                            $loop = $loop + 1;
                        }
                    } 
                    // else {
                    //     // DB::rollBack();
                    //     // return redirect()->route('allocate_task.allocation', ['id'=>$id, 'tab' => 'tab2'])->with('error', 'Allocated  no is greater than remaining vacancy to be allocated.');
                    //     return redirect()->route('allocate_task.allocation', $id)->with(['error'=> 'Allocated  no is greater than remaining vacancy to be allocated.','tab' => 'tab2']);
                    // }
                }
                // $input['requirement_id'] = $k;
                //$i = DB::table('tasks')->insertGetId($input);
            }
            // exit();
            // $i > 0 
            if ($i > 0 && $loop == count($require)) {
                // DB::commit();
                return redirect()->route('list_task.allocation', $id)->with('success', 'Allocated successfully.');
            } else if($i > 0){
                return redirect()->route('list_task.allocation', $id)->with('success', 'Allocated successfully partially! Some allocated some not allocated');
            }else {
                // DB::rollBack();
                return redirect()->route('allocate_task.allocation', $id)->with(['error'=> 'Allocated unsuccessfully.','tab' => 'tab2']);
            }


        } //One to many
        else if ($type == 3) {

            // print_r($request->all());exit();
            $loop=0;
            foreach ($employee as $k) {
                $total=0;
                $input['employee_id'] = $k;
                $total_vacancy = Client_requirement::where('id', $require)->get();
                foreach ($total_vacancy as $tv) {
                    $total = $tv->total_position;
                }
                $total_allocated = Task::where('requirement_id', $require)
                    ->where('deleted_at', NULL)
                    ->sum('allocated_no');
                $allocated_no = $request->allocated_no;
                if ($total >= $total_allocated) {
                    $remaining = $total - $total_allocated;
                    if ($remaining >= $allocated_no) {

                        // to check the task already exists for particular allocation, employe & requirement 
                        $task_exists_check = Task::where('employee_id', $k)
                        ->where('requirement_id', $require)
                        ->where('allocation_id', $request->allocation_id)->get();
                        // print_r($task_exists_check);exit();
                            if ($task_exists_check->isEmpty()) {

                            // DB::beginTransaction();
                            $input['requirement_id'] = $require;
                            $input['allocated_no'] = $request->allocated_no;
                            $input['allocation_id'] = $request->allocation_id;

                            $i = Task::insertGetId($input);
                            $loop = $loop + 1;
                        
                        }

                    } 
                    // else {
                    //     // DB::rollBack();
                    //     return redirect()->route('allocate_task.allocation', $id)->with('error', 'Allocated  no is greater than remaining vacancy to be allocated.');
                    // }
                }
                // $input['requirement_id'] = $k;
                //$i = DB::table('tasks')->insertGetId($input);
            }
            // if ($i > 0) {
            //     // DB::commit();
            //     return redirect()->route('list_task.allocation', $id)->with('success', 'Allocated successfully.');
            // } else {
            //     // DB::rollBack();
            //     return redirect()->route('allocate_task.allocation', $id)->with('error', 'Allocated unsuccessfully.');
            // }
            // new
            if ($i > 0 && $loop == count($employee)) {
                // DB::commit();
                return redirect()->route('list_task.allocation', $id)->with('success', 'Allocated successfully.');
            } else if($i > 0){
                return redirect()->route('list_task.allocation', $id)->with('success', 'Allocated successfully partially! Some allocated some not allocated');
            }else {
                // DB::rollBack();
                return redirect()->route('allocate_task.allocation', $id)->with(['error'=> 'Allocated unsuccessfully.','tab' => 'tab3']);
            }


           /* foreach ($employee as $k) {
                $input['employee_id'] = $k;

                $i = DB::table('tasks')->insertGetId($input);
            }
            if ($i > 0) {
                return redirect()->route('list_task.allocation', $id)->with('success', 'Allocated successfully.');
            } else {
                return redirect()->route('allocate_task.allocation', $id)->with('error', 'Allocated unsuccessfully.');
            }*/

        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->except(['_token', '_method', 'task_id', 'allocation_id','total_position']);
        $task_id = $request->task_id;
        $id = $request->allocation_id;
        $input['updated_by'] = Auth::user()->id;
        $input['updated_at'] = Carbon::now();

        $i = Task::where('id', $task_id)->update($input);

        if ($i > 0) {
            return redirect()->route('list_task.allocation', $id)->with('success', 'Requirement updated successfully');
        } else {
            return redirect()->route('list_task.allocation', $id)->with('error', 'Requirement conditions updated unsuccessfully');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exists = Task::find($id);

        if ($exists) {
            $input['deleted_by'] = Auth::user()->id;
            $i = Task::where('id', $id)->update($input);

            if ($i > 0) {
                $delete = $exists->delete();
                return redirect()->back()->with('success', ' Task updated  successfully');
            } else {
                return redirect()->back()->with('error', 'Task updated unsuccessfully');
            }

        } else {
            return redirect()->back()->with('error', 'Task allocation not found');
        }
    }
}
