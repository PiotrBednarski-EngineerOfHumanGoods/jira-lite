<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Attachment $attachment)
    {
        abort_unless(Storage::disk('public')->exists($attachment->path), 404);
        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }

    public function destroy(Request $request, Attachment $attachment)
    {
        $user = $request->user();
        if ($user->id !== $attachment->uploaded_by && ! $user->isManager()) {
            abort(403, 'Możesz usuwać tylko swoje załączniki.');
        }
        $taskId = $attachment->task_id;
        $attachment->delete();
        return redirect()->route('tasks.show', $taskId)
            ->with('success', 'Załącznik usunięty.');
    }
}
