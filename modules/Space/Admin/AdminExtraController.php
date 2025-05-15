<?php

namespace Modules\Space\Admin;

use App\BaseModel;
use App\Helpers\CodeHelper;
use App\Models\AiPrompts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\AdminController;
use Modules\Booking\Models\Booking;
use Yajra\DataTables\Facades\DataTables;

class AdminExtraController extends AdminController
{

    public function promptsDataTable(Request $request)
    {
        $searchFilters = request()->input('search_query');
        $searchFilters = CodeHelper::cleanArray($searchFilters);

        $query = AiPrompts::query();

        BaseModel::buildFilterQuery($query, [
            'q' => ['code', 'object_id'],
            'type' => 'type',
            'is_active' => 'is_active'
        ]);

        // CodeHelper::debugQuery($query);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('updated_at', function ($model) {
                return CodeHelper::printDateAndTime($model->updated_at);
            })
            ->addColumn('status', function ($model) {
                $html = '';
                if ($model->is_active === 1) {
                    $html = '<span class="badge badge-success">Active</span>';
                } else {
                    $html = '<span class="badge badge-primary">In-Active</span>';
                }
                $html .= view('Space::admin.prompts.form', ['model' => $model])->render();
                return $html;
            })
            ->addColumn('actions', function ($row) {
                $buttons = [
                    'edit' => ['icon' => '<i class="fa fa-pencil"></i>', 'url' => 'javascript:;', 'class' => 'edit-prompt-model', 'extra' => ['data-target' => 'promptFormModal' . $row->id]],
                    'delete' => ['icon' => '<i class="fa fa-remove"></i>', 'url' => route('admin.deletePrompt', ['id' => $row->id])],
                ];
                if ($row->is_active === 0) {
                    $buttons['active'] = ['icon' => '<i class="fa fa-check"></i>', 'url' => route('admin.markActivePrompt', ['id' => $row->id])];
                }
                return BaseModel::getActionButtons($buttons);
            })
            ->rawColumns(['actions', 'status'])
            ->make(true);

    }

    public function savePrompt(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required',
                'type' => 'required',
                'prompt' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors()->first()
                ]);
            } else {
                $model = new AiPrompts();
                if ($request->has('id') && $request->input('id') != null) {
                    $model = AiPrompts::where('id', $request->input('id'))->first();
                }
                $model->name = $request->input('name');
                $model->type = $request->input('type');
                $model->prompt = $request->input('prompt');
                if ($model->save()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Prompt has been saved',
                        'data' => $model
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'Failed to save prompt'
                    ]);
                }

            }
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request'
            ]);
        }
    }

    public function markActivePrompt()
    {
        if (isset($_GET['id'])) {
            $model = AiPrompts::where('id', $_GET['id'])->first();
            if ($model != null) {
                $model->markAsActive();
                return redirect()->back()->with('success', 'AI-Prompt mark activated');
            } else {
                return redirect()->back()->with('error', 'Prompt not found');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid Request');
        }
    }

    public function deletePrompt()
    {
        if (isset($_GET['id'])) {
            AiPrompts::where('id', $_GET['id'])->delete();
            return redirect()->back()->with('success', 'AI-Prompt has been deleted');
        } else {
            return redirect()->back()->with('error', 'Invalid Request');
        }
    }

}