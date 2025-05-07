<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Dashboard</h3>
            </div>
            <div class="card-body text-center">
                <?php if($user['profile_image']): ?>
                    <img src="<?= base_url('uploads/' . $user['profile_image']) ?>"class="profile-image-large" alt="Profile">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" class="profile-image-large mb-3" alt="Profile">
                <?php endif; ?>
                
                <h2>Welcome, <?= $user['name'] ?>!</h2>
                <p>This is your dashboard. You can navigate to other sections using the menu above.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
