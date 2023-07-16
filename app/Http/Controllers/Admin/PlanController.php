<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use DataTables;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $plans = Plan::query();

        if ($request->ajax()) {

            $token = csrf_token();

            return DataTables::of($plans)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn =
                        '<a class="btn btn-xs btn-success mx-1 shadow" title="Ver" href="plans/' . $row->id . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>' .
                        '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="plans/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                        '<form method="POST" action="plans/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste plano?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.plans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanRequest $request)
    {
        $data = $request->all();
        Plan::create($data);
        return redirect()->route('plans.index')->with('success', 'Plano cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->route('plans.index')->with('error', 'Plano não encontrado!');
        }

        return view('admin.pages.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->route('plans.index')->with('error', 'Plano não encontrado!');
        }

        return view('admin.pages.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlanRequest $request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->route('plans.index')->with('error', 'Plano não encontrado!');
        }

        $data = $request->all();
        $plan->update($data);

        return redirect()->route('plans.index')->with('success', 'Plano atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->route('plans.index')->with('error', 'Plano não encontrado!');
        }

        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Plano deletado com sucesso!');
    }
}
