<?= $this->include('admin/sidebar') ?>
<h2>Edit Question</h2>

<form action="/admin/updateQuestion/<?= $question['id'] ?>" method="post">
    <div class="form-group mb-3">
        <label for="question">Question</label>
        <input type="text" name="question" class="form-control" value="<?= $question['question'] ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="answer_type">Answer Type</label>
        <select name="answer_type" class="form-control">
            <option value="text" <?= ($question['answer_type'] == 'text') ? 'selected' : '' ?>>Text</option>
            <option value="date" <?= ($question['answer_type'] == 'date') ? 'selected' : '' ?>>Date Field</option>
            <option value="link" <?= ($question['answer_type'] == 'link') ? 'selected' : '' ?>>Link</option>
            <option value="file" <?= ($question['answer_type'] == 'file') ? 'selected' : '' ?>>File Upload</option>
            <option value="yes_no" <?= ($question['answer_type'] == 'yes_no') ? 'selected' : '' ?>>Yes / No</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Update Question</button>
</form>
