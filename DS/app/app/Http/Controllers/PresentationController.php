<?php

namespace App\Http\Controllers;

use App\Presentation\PresentationService;
use App\Presentation\Slide;
use App\Presentation\SlideIndicator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PresentationController extends Controller
{


    public function getTemplates()
    {
        return $this->presentation->getTemplates();
    }

    public function getSlides()
    {
        return Slide::orderBy('order', 'asc')->get();
    }

    public function createSlide(Request $request)
    {
        return Slide::create();
    }

    public function savePresentation(Request $request)
    {
        $data = $request->validate([
            'presentation' => 'required|json'
        ]);
        $pre = json_decode($data['presentation']);

        // :(
        Slide::truncate();
        SlideIndicator::truncate();

        $error = false;
        foreach ($pre as $slide) {
            if (!is_object($slide)) {
                $error = "Slide não é um objeto";
                break;
            }
            if (!isset($slide->timer, $slide->order)) {
                $error = "Slide não tem as propriedades necessárias!";
                break;
            }
            $time = $slide->timer;
            $order = $slide->order;
            $s = Slide::create(['time' => $time, 'order' => $order]);
            $indicators = $slide->slide;
            if (is_array($indicators)) {
                $y = 0;
                foreach ($indicators as $row) {
                    $success = $s->setIndicators($y, $row);
                    if ($success !== true) {
                        $error = $success;
                        break 2;
                    }
                    $y++;
                }
            }
        }
        if ($error !== false) {
            throw  \Illuminate\Validation\ValidationException::withMessages([
                'presentation' => [$error]
            ]);
        }
        return response()->json(['success' => 'true', 'presentation' => Slide::getPresentation()]);
    }

    public function getPresentation()
    {
        return Slide::getPresentation();
    }

    public function deleteSlide(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:slides,id'
        ]);
        $success = Slide::find($data['id'])->delete();
        return response()->json(['success' => $success], $success ? 200 : 403);
    }

    public function setIndicators(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:slides,id',
            'indicators' => 'required|array',
            'indicators.*' => 'integer|exists:indicators,id'
        ]);
        $indicators = $data['indicators'];

        $slide = Slide::find($data['id']);

        $received_size = count($indicators);
        if ($received_size >= 4) {
            throw  \Illuminate\Validation\ValidationException::withMessages([
                'indicators' => ['Recebido tamanho maior do que esperado!']
            ]);
        }
        $success = $slide->setIndicators($data['slot'], $indicators);
        return response()->json(['success' => $success], $success ? 200 : 403);

    }

    public function setOrder(Request $request)
    {
        $data = $request->validate([
            'slide' => 'required|array',
            'slide.*' => 'integer|exists:slides,id'
        ]);
        // Ordena na ordem que recebida
        $slides = $data['slide'];
        $order = 1;
        foreach ($slides as $id) {
            $slide = Slide::find($id);
            $slide->order = $order;
            $slide->save();
            $order++;
        }
        // Agora bota os que não foram especificados no final
        foreach (Slide::all() as $slide) {
            if (!in_array($slide->id, $slides)) {
                $slide->order = $order;
                $slide->save();
                $order++;
            }
        }
        return response()->json(['success' => true]);
    }


}
