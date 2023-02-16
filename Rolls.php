<?php


namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Roll;


class Rolls extends Component
{
    public $rolls, $name, $description, $roll_id;
    public $updateMode = false;

    public function render()
    {
        $this->rolls = Roll::all();
        return view('livewire.rolls');
    }

    private function resetInputFields(){
        $this->name = '';
        $this->description = '';
       
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'description' => 'required',
            
        ]);

        Roll::create($validatedDate);

        session()->flash('message', 'Rolls Created Successfully.');

        $this->resetInputFields();

        $this->emit('rollStore'); // Close model to using to jquery

    }

    public function edit($id)
    {
        $this->updateMode = true;
        $roll = Roll::where('id',$id)->first();
        $this->roll_id = $id;
        $this->name = $roll->name;
        $this->description = $roll->description;
        
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();


    }

    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'description' => 'required',
            
        ]);

        if ($this->roll_id) {
            $roll = Roll::find($this->roll_id);
            $roll->update([
                'name' => $this->name,
                'description' => $this->description,
               
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Rolls Updated Successfully.');
            $this->resetInputFields();

        }
    }

    public function delete($id)
    {
        if($id){
            Roll::where('id',$id)->delete();
            session()->flash('message', 'Rolls Deleted Successfully.');
        }
    }
}
