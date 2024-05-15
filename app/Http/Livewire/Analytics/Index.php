<?php

namespace App\Http\Livewire\Analytics;

use DB;
use App\Models\User;
use App\Models\Course;
use Livewire\Component;
use App\Models\Activity;
use App\Models\Institution;

class Index extends Component
{

    public $range;
    public $ticks;
    public $filter;
    public $course;
    public $courses;
    public $section;
    public $sections;
    public $institutions;

    protected $listeners = ['resetChart' => 'render'];

    public function mount(){
        $this->filter   = 0;
        $this->range    = 'month';
        $this->courses  = [];
        $this->institutions = Institution::all();
        // foreach($this->institutions as $institution){
        //     $this->sections[$institution->id] = array_filter(User::where('institution_id', $institution->id)->select('section')->groupBy('section')->get()->pluck('section')->toArray());
        // }

    }

    public function render()
    {

        $data = array();

        foreach($this->institutions as $institution){

            $tick_data = array();

            $data[$institution->id]['label'] = $institution->name;

            $ticks = Activity::whereHas('user', function($q) use ($institution){
                        $q->where('institution_id', $institution->id);
                    })
                    ->where('event', 'has successfully logged in')
                    ->where('created_at', '>=', '2022-08-01 00:00:01')
                    ->where('created_at', '<=', '2022-12-31 11:59:59')
                    ->select(
                            DB::raw("(count(id)) as total"),
                            DB::raw("(DATE_FORMAT(created_at, '{$this->getDateFormat()}')) as month_year"))
                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '{$this->getDateFormat()}')"))
                        ->orderBy('month_year', 'ASC')
                        ->get()->values()->toArray();

            $counter = 0;

            foreach($ticks as $tick){

                $tick_data[$counter]['id'] = $tick['month_year'];
                $tick_data[$counter]['nested'] = array('value' => $tick['total']);  

                $counter++;
            }

            $data[$institution->id]['data'] = $tick_data;
            $color = $this->colorGenerate();
            $data[$institution->id]['borderColor'] = $color;
            $data[$institution->id]['backgroundColor'] = $color;

        }

        $this->ticks = json_encode(array_values($data));

        return view('livewire.analytics.index');
    }

    public function updateFilter(){

        $filterchecker = (int)$this->filter > 0;
        $filter = (int)$this->filter;
        $data = [];

        if($filterchecker){

            $this->courses = Course::where('institution_id', $filter)->where('status', 1)->where('isOnline', 1)->get();

            foreach($this->courses as $course){

                $students = $course->students->pluck('id');

                $tick_data = array();

                $data[$course->name]['label'] = $course->name;

                $ticks = Activity::with('user')
                        ->whereIn('user_id', $students)
                        ->where('event', 'has successfully logged in')
                        ->where('created_at', '>=', '2022-08-01 00:00:01')
                        ->where('created_at', '<=', '2022-12-31 11:59:59')
                        ->select(
                                DB::raw("(count(id)) as total"),
                                DB::raw("(DATE_FORMAT(created_at, '{$this->getDateFormat($this->range)}')) as month_year"))
                            ->groupBy(DB::raw("DATE_FORMAT(created_at, '{$this->getDateFormat($this->range)}')"))
                            ->orderBy('month_year', 'ASC')
                            ->get()->values()->toArray();

                $counter = 0;

                foreach($ticks as $tick){

                    $tick_data[$counter]['id'] = $tick['month_year'];
                    $tick_data[$counter]['nested'] = array('value' => $tick['total']);  

                    $counter++;
                }

                $data[$course->name]['data'] = $tick_data;
                $color = $this->colorGenerate();
                $data[$course->name]['borderColor'] = $color;
                $data[$course->name]['backgroundColor'] = $color;
            }

        }

        $this->ticks = json_encode(array_values($data));

        $this->emit('updatedFilter', $this->ticks);
    }

    public function updateSection(){
        $filterchecker = (int)$this->section != '';
        $section = $this->section;
        $data = [];

        $tick_data = array();

        $data[$section]['label'] = 'Section ' . $section;

        $ticks = Activity::whereHas('user', function($q) use ($section){
                    $q->where('section', $section);
                })
                ->where('event', 'has successfully logged in')
                ->where('created_at', '>=', '2022-08-01 00:00:01')
                ->where('created_at', '<=', '2022-12-31 11:59:59')
                ->select(
                        DB::raw("(count(id)) as total"),
                        DB::raw("(DATE_FORMAT(created_at, '{$this->getDateFormat($this->range)}')) as month_year"))
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '{$this->getDateFormat($this->range)}')"))
                    ->orderBy('month_year', 'ASC')
                    ->get()->values()->toArray();

        $counter = 0;

        foreach($ticks as $tick){

            $tick_data[$counter]['id'] = $tick['month_year'];
            $tick_data[$counter]['nested'] = array('value' => $tick['total']);  

            $counter++;
        }

        $data[$section]['data'] = $tick_data;
        $color = $this->colorGenerate();
        $data[$section]['borderColor'] = $color;
        $data[$section]['backgroundColor'] = $color;

        $this->ticks = json_encode(array_values($data));

        $this->emit('updatedSection', $this->ticks);
    }

    public function resetChart(){

        $this->filter = 0;
        $this->range = 'month';

        $data = array();

        foreach($this->institutions as $institution){

            $tick_data = array();

            $data[$institution->id]['label'] = $institution->name;

            $ticks = Activity::whereHas('user', function($q) use ($institution){
                        $q->where('institution_id', $institution->id);
                    })
                    ->where('event', 'has successfully logged in')

                    ->select(
                            DB::raw("(count(id)) as total"),
                            DB::raw("(DATE_FORMAT(created_at, '{$this->getDateFormat($this->range)}')) as month_year"))
                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '{$this->getDateFormat($this->range)}')"))
                        ->orderBy('month_year', 'ASC')
                        ->get()->values()->toArray();

            $counter = 0;

            foreach($ticks as $tick){

                $tick_data[$counter]['id'] = $tick['month_year'];
                $tick_data[$counter]['nested'] = array('value' => $tick['total']);  

                $counter++;
            }

            $data[$institution->id]['data'] = $tick_data;
            $color = $this->colorGenerate();
            $data[$institution->id]['borderColor'] = $color;
            $data[$institution->id]['backgroundColor'] = $color;

        }

        $this->ticks = json_encode(array_values($data));

        $this->emit('resetChart', $this->ticks);
    }

    public function colorGenerate(){
        return '#' . dechex(rand(0x000000, 0xFFFFFF));
    }

    protected function getDateFormat($range = 'month'){
        switch($range){
            case 'year':
                $date_format = '%Y';
                break;
            
            case 'week':
                $date_format = '%v-%x';
            break;

            case 'month':
            default:
                $date_format = '%m-%Y';
            break;
        }

        return $date_format;
    }
}
