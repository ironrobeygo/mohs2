<?php

namespace App\Http\Livewire\User\Student;

use App\Models\User;
use App\Models\Course;
use Livewire\Component;
use App\Models\Institution;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\StudentCreated;
use Illuminate\Database\QueryException;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Batch extends Component
{
    use WithFileUploads;

    public $batch_upload;

    public function render()
    {
        return view('livewire.user.student.batch');
    }

    public function batchUser(){

        $basePath = $this->batch_upload->getRealPath();

        try{

            SimpleExcelReader::create($basePath, 'csv')
                ->getRows()
                ->each(
                    function(array $data){

                        // $password = Str::random(16);
                        $password = 'MOHS.USER';

                        $institution = Institution::where('name', trim($data['institution']))->firstOrFail();

                        $course = Course::where('name', trim($data['course']))->firstOrFail();

                        DB::beginTransaction();

                        $user = User::create([
                            'name'              => $data['name'] . ' ' . $data['last_name'],
                            'email'             => $data['email'],
                            'section'           => $data['section'],
                            'password'          => Hash::make($password),
                            'institution_id'    => $institution->id
                        ]);

                        $user->assignRole('student');

                        $course->enrolStudent($user->id);

                        // $updatedPassword = $institution->alias.$user->id;
                        // $updatedPassword = $password;
                        // $user->update([ 'password' => Hash::make($updatedPassword) ]);

                        // $data['password'] = $updatedPassword;

                        // Notification::send($user, new StudentCreated($data));

                        DB::commit();
                    }
                );

            alert()->success("Batch upload successful.", 'Congratulations!');

        } catch(QueryException $e){
            DB::rollback();
            alert()->error($e->getMessage(), 'Please try again!');

        } catch(ModelNotFoundException $h){
            DB::rollback();
            alert()->error($h->getMessage(), 'Please try again!');

        } catch(Throwable $e){
            DB::rollback();
            alert()->error($e->getMessage(), 'Please try again');
        }

        return redirect()->to('/students');
        
    }
}
