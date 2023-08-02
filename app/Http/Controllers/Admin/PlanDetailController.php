<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanDetailRequest;
use App\Models\Plan;
use App\Models\PlanDetail;
use Illuminate\Http\Request;
use DataTables;

class PlanDetailController extends Controller
{

    protected $repository, $plan;

    public function __construct(PlanDetail $planDetail, Plan $plan)
    {
        $this->repository = $planDetail;
        $this->plan = $plan;

        // $this->middleware(['can:plans']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $planId)
    {
        CheckPermission::checkAuth('Listar Detalhes');

        if (!$plan = $this->plan->find($planId)) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $details = $plan->details();

        if ($request->ajax()) {

            $token = csrf_token();

            return DataTables::of($details)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token, $plan) {
                    $btn =
                        '<a class="btn btn-xs btn-success mx-1 shadow" title="Ver" href="details/' . $row->id . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>' .
                        '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="details/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                        '<form method="POST" action="details/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste detalhe?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.plans.details.index', compact('plan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($planId)
    {
        CheckPermission::checkAuth('Criar Detalhes');

        if (!$plan = $this->plan->find($planId)) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        return view('admin.pages.plans.details.create', compact('plan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanDetailRequest $request, $planId)
    {
        CheckPermission::checkAuth('Criar Detalhes');

        if (!$plan = $this->plan->find($planId)) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $plan->details()->create($request->all());

        return redirect()->route('admin.details.index', ['id' => $plan->id])->with('success', 'Detalhe do Plano cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($planId, $detailId)
    {
        CheckPermission::checkAuth('Listar Detalhes');

        if (!$plan = $this->plan->find($planId)) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $detail = PlanDetail::find($detailId);

        if (!$detail) {
            return redirect()->route('admin.details.index', ['id' => $plan])->with('error', 'Detalhe não encontrado!');
        }

        return view('admin.pages.plans.details.show', compact('plan', 'detail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($planId, $detailId)
    {
        CheckPermission::checkAuth('Editar Detalhes');

        if (!$plan = $this->plan->find($planId)) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $detail = PlanDetail::find($detailId);

        if (!$detail) {
            return redirect()->route('details.index', ['id' => $plan])->with('error', 'Detalhe não encontrado!');
        }

        return view('admin.pages.plans.details.edit', compact('plan', 'detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlanDetailRequest $request, $planId, $detailId)
    {
        CheckPermission::checkAuth('Editar Detalhes');

        if (!$plan = $this->plan->find($planId)) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $detail = PlanDetail::find($detailId);

        if (!$detail) {
            return redirect()->route('admin.details.index', ['id' => $plan])->with('error', 'Detalhe não encontrado!');
        }

        $detail->update($request->all());

        return redirect()->route('admin.details.index', ['id' => $plan->id])->with('success', 'Detalhe do Plano atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($planId, $detailId)
    {
        CheckPermission::checkAuth('Excluir Detalhes');

        if (!$plan = $this->plan->find($planId)) {
            return redirect()->route('admin.plans.index')->with('error', 'Plano não encontrado!');
        }

        $detail = PlanDetail::find($detailId);

        if (!$detail) {
            return redirect()->route('admin.details.index', ['id' => $plan])->with('error', 'Detalhe não encontrado!');
        }

        $detail->delete();

        return redirect()->route('admin.details.index', ['id' => $plan->id])->with('success', 'Detalhe do Plano excluído com sucesso!');
    }
}
