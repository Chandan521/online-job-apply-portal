<div>
    <h5 class="fw-bold mb-3">Manage Resume</h5>
    @if (!empty($user->resume))
        <div class="mb-3">
            <a href="{{ Storage::url($user->resume) }}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">View Current Resume (PDF)</a>
            <div class="border rounded overflow-hidden mb-3" style="height: 350px; width: 100%; max-width: 600px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                <canvas id="pdfViewer" style="max-width:100%; max-height:100%; display:block; margin:auto;"></canvas>
            </div>
            <form method="POST" action="{{ route('user.settings.resume.delete') }}" class="d-inline ms-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Delete Resume</button>
            </form>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const url = "{{ asset('storage/' . ltrim($user->resume, '/')) }}";
            console.log('PDF URL:', url);
            if (window['pdfjsLib']) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                const loadingTask = pdfjsLib.getDocument(url);
                loadingTask.promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        // Get original viewport to determine scale
                        const originalViewport = page.getViewport({ scale: 1 });
                        const maxWidth = 600;
                        const maxHeight = 350;
                        let scale = 1.5;
                        if (originalViewport.width > 0 && originalViewport.height > 0) {
                            scale = Math.min(1.5, maxWidth / originalViewport.width, maxHeight / originalViewport.height);
                        }
                        const viewport = page.getViewport({ scale: scale });
                        const canvas = document.getElementById('pdfViewer');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext).promise.catch(function(error) {
                            console.error('Render error:', error);
                            canvas.outerHTML = '<div class="text-danger p-3">Failed to render PDF page.</div>';
                        });
                    }).catch(function(error) {
                        console.error('Get page error:', error);
                        document.getElementById('pdfViewer').outerHTML = '<div class="text-danger p-3">Failed to render PDF page.</div>';
                    });
                }).catch(function(error) {
                    console.error('PDF load error:', error);
                    document.getElementById('pdfViewer').outerHTML = '<div class="text-danger p-3">Failed to load PDF file. Check the file path and permissions.</div>';
                });
            } else {
                document.getElementById('pdfViewer').outerHTML = '<div class="text-danger p-3">PDF.js library not loaded.</div>';
            }
        });
        </script>
    @endif
    <form method="POST" action="{{ route('user.settings.resume.upload') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Upload New Resume (PDF, max 2MB)</label>
            <input type="file" name="resume" class="form-control" accept="application/pdf">
        </div>
        <button type="submit" class="btn btn-primary">Upload Resume</button>
    </form>
</div>
