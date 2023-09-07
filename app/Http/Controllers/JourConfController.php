<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\JourConf;
use Helper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JourConfController extends Controller
{
    public function index()
    {
        $jourConfs = Institution::find(Helper::getInstitutionId())->jourConfs;
        return Inertia::render('Eglise/JourConfsView', compact('jourConfs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required',
        ]);
        JourConf::create($request->all());
    }

    public function update($id, Request $request)
    {
        JourConf::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $annonce = JourConf::findOrFail($id);
        $annonce->delete();
    }
}
