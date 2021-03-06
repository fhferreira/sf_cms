<?php

namespace App\Controllers\Admin;

use BaseController, Redirect, Sentry, View, DB, Input, Validator, Page;

class PageController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $pages = DB::table('pages')->paginate(15);
        return View::make('backend.page.index', compact('pages'))->with('active', 'page');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        return View::make('backend.page.create')->with('active', 'page');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {

        $formData = array(
            'title'   => Input::get('title'),
            'content' => Input::get('content'),
            'is_menu' => Input::get('is_menu')
        );

        $rules = array(
            'title'   => 'required',
            'content' => 'required'
        );

        $validation = Validator::make($formData, $rules);

        if ($validation->fails()) {
            return Redirect::action('App\Controllers\Admin\PagesController@create')->withErrors($validation)->withInput();
        }

        $page = new Page();
        $page->title = $formData['title'];
        $page->content = $formData['content'];
        $page->is_menu = ($formData['is_menu']) ? true : false;
        $page->save();

        return Redirect::action('App\Controllers\Admin\PageController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id) {

        $page = Page::find($id);
        return View::make('backend.page.show', compact('page'))->with('active', 'page');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {

        $page = Page::find($id);
        return View::make('backend.page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {

        $formData = array(
            'title'   => Input::get('title'),
            'content' => Input::get('content'),
            'is_menu' => Input::get('is_menu')
        );

        $page = Page::find($id);
        $page->title = $formData['title'];
        $page->content = $formData['content'];
        $page->is_menu = $formData['is_menu'];

        $page->save();
        return Redirect::action('App\Controllers\Admin\PageController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id) {

        $page = Page::find($id);
        $page->delete();

        return Redirect::action('App\Controllers\Admin\PageController@index');
    }

    public function confirmDestroy($id) {

        $page = Page::find($id);
        return View::make('backend.page.confirm-destroy', compact('page'))->with('active', 'page');
    }
}
