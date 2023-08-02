<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use App\Models\PlanProfile;
use Illuminate\Http\Request;
use DataTables;
use Spatie\Permission\Models\Role;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Planos');

        $plans = Plan::query();

        if ($request->ajax()) {

            $token = csrf_token();

            return DataTables::of($plans)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn =
                        '<a class="btn btn-xs btn-info mx-1 shadow" title="Detalhes" href="plans/' . $row->id . '/details"><i class="fa fa-lg fa-fw fa-asterisk"></i></a>' .
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
        CheckPermission::checkAuth('Criar Planos');

        $roles = Role::orderBy('name')->select('id', 'name')->get();

        return view('admin.pages.plans.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanRequest $request)
    {
        CheckPermission::checkAuth('Criar Planos');

        $data = $request->all();
        $plan = Plan::create($data);

        foreach ($request->roles as $item) {
            PlanProfile::create([
                'plan_id' => $plan->id,
                'role_id' => $item,
            ]);
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plano cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        CheckPermission::checkAuth('Listar Planos');

        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $roles = Role::select('id', 'name')->get();

        $profiles = [];
        foreach ($roles as $role) {
            if (in_array($role->id, $plan->profiles->pluck('role_id')->toArray())) {
                $profiles[] = $role->name;
            }
        }

        return view('admin.pages.plans.show', compact('plan', 'profiles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Planos');

        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $roles = Role::orderBy('name')->select('id', 'name')->get();

        return view('admin.pages.plans.edit', compact('plan', 'roles'));
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
        CheckPermission::checkAuth('Editar Planos');

        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $data = $request->all();
        $plan->update($data);

        $plan->profiles()->delete();
        foreach ($request->roles as $item) {
            PlanProfile::create([
                'plan_id' => $plan->id,
                'role_id' => $item,
            ]);
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plano atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CheckPermission::checkAuth('Excluir Planos');

        $plan = Plan::with('details')->find($id);

        if (!$plan) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        if ($plan->details()->count() > 0) {
            return redirect()->route('admin.plans.index')->with('error', 'Existem detalhes vinculados a esse plano, portanto não pode deletar');
        }

        $plan->delete();
        $plan->profiles()->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plano deletado com sucesso!');
    }
}
