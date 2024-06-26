<?php

namespace App\Http\Livewire\Courses\Chapters\Quiz\Questions;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\Option;
use App\Models\Chapter;
use Livewire\Component;
use App\Models\Question;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $course;
    public $chapter;
    public $quiz;
    public $type_id;
    public $question;
    public $weight;
    public $questionValue;
    public $showOptionsForm = false;
    public $options = [];
    public $medias;
    public $attachments;
    public $identificationField;
    public $identify;

    public function mount(Course $course, Chapter $chapter, Quiz $quiz, Question $question){
        $this->course = $course;
        $this->chapter = $chapter;
        $this->quiz = $quiz;
        $this->question = $question;
        $this->questionValue = $question->question;
        $this->type_id = $question->type_id;
        $this->options = $question->options->toArray();
        $this->showOptionsForm = $question->type_id == 1 ? true : false;
        $this->medias   = $question->getMedia('images');
        $this->weight = is_null($question->weight) ? 1 : $question->weight;  
        $this->identificationField = $this->type_id == 4 ? true : false;
        $this->identify = $this->type_id == 4 ? $this->question->answerKey->answer : '';
    }

    public function multipleChoice($value){
        if( $value == 1){
            $this->showOptionsForm = true;
        } else {
            $this->showOptionsForm = false;
            $this->options = [
                ['value' => '', 'answer' => false]
            ];
        }

        $this->dispatchBrowserEvent('contentChanged');
    }

    public function editQuestion(){

        $this->validate();

        $data = [
            'question'  => $this->questionValue,
            'type_id'   => $this->type_id,
            'updated_by' => auth()->user()->id,
            'status'    => auth()->user()->hasRole('admin') ? Question::ACTIVE : Question::PENDING,
            'weight' => $this->weight
        ];

        $this->question->update($data);

        if($this->type_id == 1){

            $options = array();

            foreach($this->options as $option){

                $opt = new Option;
                $opt->value = $option['value'];
                $opt->answer = $option['answer'];
                $opt->save();

                $options[] = $opt->id;

            }

            $this->question->syncOptions($options);
        }

        return redirect()->to('/courses/'.$this->course->id.'/chapters/'.$this->chapter->id.'/quiz/'.$this->quiz->id);

    }

    public function addOption(){
        $this->options[] = [
            'value' => '',
            'answer' => false
        ];
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedAttachments(){
        $filename = pathinfo($this->attachments->getClientOriginalName(), PATHINFO_FILENAME);
        $media = auth()->user()->addMedia($this->attachments->getRealPath())
            ->usingName($filename)
            ->usingFileName($this->attachments->getClientOriginalName())
            ->toMediaCollection('files');

        $this->dispatchBrowserEvent('resetFileUploader', ['uploadedUrl' => $media->getFullUrl(), 'filename' => $filename]);
    }
    
    public function removeMedia($index){
        $this->medias[$index]->delete();
        unset($this->medias[$index]);
    }

    public function render()
    {
        return view('livewire.courses.chapters.quiz.questions.edit');
    }

    protected function rules(){
        return [
            'question'  => 'required'
        ];
    }
}
