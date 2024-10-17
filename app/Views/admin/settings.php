<?= $this->include('admin/sidebar') ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<h2>Manage Questions</h2>

<!-- Form to add a new question -->
<form action="/admin/addQuestion" method="post">
    <div class="form-group mb-3">
        <label for="question">Question</label>
        <input type="text" name="question" class="form-control" placeholder="Enter Question" required>
    </div>

    <div class="form-group mb-3">
        <label for="answer_type">Answer Type</label>
        <select name="answer_type" class="form-control">
            <option value="text">Text</option>
            <option value="date">Date Field</option>
            <option value="link">Link</option>
            <option value="file">File Upload</option>
            <option value="yes_no">Yes / No</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Add Question</button>
</form>

<h3>Existing Questions</h3>
<table class="table">
    <thead>
        <tr>
            <th>Question</th>
            <th>Answer Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($questions as $question): ?>
            <tr>
                <td><?= $question['question'] ?></td>
                <td><?= $question['answer_type'] ?></td>
                <td>
                    <!-- Edit Button -->
                    <a href="/admin/editQuestion/<?= $question['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <!-- Delete Button -->
                    <form action="/admin/deleteQuestion/<?= $question['id'] ?>" method="post" style="display:inline;">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


