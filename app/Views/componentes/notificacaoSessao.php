<?php if (
    null !== (session()->get('msg'))
    || (session()->get('success'))
    || (session()->get('errors'))
): ?>
    <div class="callout <?= session()->has('errors') ? 'callout-danger' : 'callout-info' ?>">
        <?php if (session()->has('msg')): ?>
            <p><?= esc(session('msg')) ?></p>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <p><?= esc(session('success')) ?></p>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <?php if (is_array(session('errors'))): ?>
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p><?= esc(session('errors')) ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>