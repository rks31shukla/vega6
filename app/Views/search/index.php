<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Search for Images/Videos</h3>
            </div>
            <div class="card-body">
                <form action="<?= site_url('/search') ?>" method="get">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="query" value="<?= $query ?? '' ?>" placeholder="Search for images or videos..." required>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="type">
                                <option value="images" <?= (isset($type) && $type === 'images') ? 'selected' : '' ?>>Images</option>
                                <option value="videos" <?= (isset($type) && $type === 'videos') ? 'selected' : '' ?>>Videos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </div>
                </form>
                
                <?php if(isset($results) && !empty($results)): ?>
                    <div class="mt-4">
                        <h4>Search Results for "<?= $query ?>"</h4>
                        
                        <div class="row">
                            <?php if($type === 'images'): ?>
                                <?php foreach($results as $image): ?>
                                    <div class="col-md-3 mb-4">
                                        <div class="card h-100">
                                            <img src="<?= $image['webformatURL'] ?>" class="card-img-top" alt="<?= $image['tags'] ?>">
                                            <div class="card-body">
                                                <p class="card-text">
                                                    <small class="text-muted">By: <?= $image['user'] ?></small><br>
                                                    <small class="text-muted">Tags: <?= $image['tags'] ?></small>
                                                </p>
                                                <a href="<?= $image['largeImageURL'] ?>" class="btn btn-sm btn-primary" target="_blank">View Full Size</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php foreach($results as $video): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <video controls class="card-img-top">
                                                <source src="<?= $video->videos->medium->url ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <div class="card-body">
                                                <p class="card-text">
                                                    <small class="text-muted">By: <?= $video->user ?></small><br>
                                                    <small class="text-muted">Tags: <?= $video->tags ?></small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif(isset($query)): ?>
                    <div class="alert alert-info mt-4">
                        No results found for "<?= $query ?>". Try different keywords.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
