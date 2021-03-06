<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;

class QuestionController extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::latest()->paginate(5);
        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question;

        return view('questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title', 'body'));

        return redirect()->route('questions.index')->with('success', 'Your Questions has been submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->increment('views');


// below two line code in uper one line ...same as up and below two line.
//        $question->views = $question->views + 1;
//        $question->save();

        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        /**this code for Using GATE */
//        if(\Gate::denies('update-question', $question)){
//            abort(404, 'Access Denied');
//        }
//        return view('questions.edit', compact('question'));


        /**this code for Using Policy */

        $this->authorize("update", $question);
        return view('questions.edit', compact('question'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        /**this code for Using GATE */
//        if(\Gate::denies('update-question', $question)){
//            abort(404, 'Access Denied');
//        }
//        $question->update($request->only('title', 'body'));
//
//        return redirect('/questions')->with('success',"Your Question has been updated.");

        /**this code for Using POLICIES */
        $this->authorize("update", $question);
        $question->update($request->only('title', 'body'));

        return redirect('/questions')->with('success',"Your Question has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        /**this code for Using GATE */
//        if(\Gate::denies('delete-question', $question)){
//            abort(404, 'Access Denied');
//        }
//        $question->delete();
//
//        return redirect('/questions')->with('success', "Your question has been successfully deleted.");

        /** This code for using POLICIES */
        $this->authorize("delete", $question);
        $question->delete();
        return redirect('/questions')->with('success', "Your question has been successfully deleted.");
    }
}
