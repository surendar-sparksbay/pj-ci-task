<?= $this->include('client/sidebar') ?>
<h2>Answer the Questionnaire</h2>

<form action="/client/submitAnswers" method="post" enctype="multipart/form-data">
    <?php foreach ($questions as $question): ?>
        <div class="form-group mb-3">
            <label><?= $question['question'] ?></label>

            <?php
            // Check if an answer exists for this question
            $existingAnswer = isset($answersByQuestionId[$question['id']]['answer']) ? $answersByQuestionId[$question['id']]['answer'] : '';
            $existingFilePath = isset($answersByQuestionId[$question['id']]['file_path']) ? $answersByQuestionId[$question['id']]['file_path'] : '';
            $existingLinkScreenshot = isset($answersByQuestionId[$question['id']]['link_screenshot_path']) ? $answersByQuestionId[$question['id']]['link_screenshot_path'] : '';
            $existingUrl = isset($answersByQuestionId[$question['id']]['url']) ? $answersByQuestionId[$question['id']]['url'] : "";
            ?>

            <?php if ($question['answer_type'] == 'text'): ?>
                <input type="text" name="<?= $question['id'] ?>" class="form-control" value="<?= esc($existingAnswer) ?>">

            <?php elseif ($question['answer_type'] == 'date'): ?>
                <input type="date" name="<?= $question['id'] ?>" class="form-control" value="<?= esc($existingAnswer) ?>">

            <?php elseif ($question['answer_type'] == 'link'): ?>
                <input type="url" name="<?= $question['id'] ?>" class="form-control url-input"  data-question-id="<?= $question['id'] ?>"  value="<?= esc($existingUrl) ?>">
                <div id="screenshot-preview-<?= $question['id'] ?>" class="screenshot-container">
                    <!-- Screenshot will be loaded here -->
                </div>
                <?php if ($existingLinkScreenshot): ?>
                    <div>
                        <img src="<?= base_url('uploads/screenshots/'.$existingLinkScreenshot) ?>" alt="Screenshot" style="max-width: 200px; margin-top: 10px;">
                    </div>
                <?php endif; ?>

            <?php elseif ($question['answer_type'] == 'file'): ?>
                <input type="hidden" name="<?= $question['id'] ?>" class="form-control">
                <input type="file" name="<?= $question['id'] ?>" class="form-control">
                <?php if ($existingFilePath): ?>
                    <div>
                        <a href="<?= base_url($existingFilePath) ?>" target="_blank">View Uploaded File</a>
                    </div>
                <?php endif; ?>

            <?php elseif ($question['answer_type'] == 'yes_no'): ?>
                <div>
                    <label><input type="radio" name="<?= $question['id'] ?>" value="yes" <?= ($existingAnswer == 'yes') ? 'checked' : '' ?>> Yes</label>
                    <label><input type="radio" name="<?= $question['id'] ?>" value="no" <?= ($existingAnswer == 'no') ? 'checked' : '' ?>> No</label>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-primary">Submit Answers</button>
</form>
<script>
    document.querySelectorAll('.url-input').forEach(function(inputField) {
        inputField.addEventListener('blur', function() {
            var url = this.value;
            var questionId = this.getAttribute('data-question-id');

            if (url) {
                // Make AJAX call to fetch screenshot
                fetch('/client/captureScreenshotAjax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>' // CSRF protection in CodeIgniter 4
                    },
                    body: JSON.stringify({ url: url, question_id: questionId })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('data', data);
                    if (data.screenshot) {
                        // Show the screenshot in the preview div
                        var screenshotPreview = document.getElementById('screenshot-preview-' + questionId);
                        screenshotPreview.innerHTML = '<img src="' + data.screenshot + '" alt="Screenshot" style="max-width: 200px;">';
                        // console.log()
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
