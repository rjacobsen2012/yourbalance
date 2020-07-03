<?php
/**
 * @var \Illuminate\Support\Collection|\Laravel\Telescope\EntryResult[] $entries
 */


?>
<?php $__env->startComponent('telescope-toolbar::item', ['name' => 'redis', 'link' => true]); ?>

    <?php $__env->slot('icon'); ?>
        <?php echo file_get_contents('/Users/richardjacobsen/development/rj-app/vendor/fruitcake/laravel-telescope-toolbar/resources/icons/' . basename('redis') . '.svg'); ?>

        <span class="sf-toolbar-value"><?php echo e($entries->count()); ?></span>

    <?php $__env->endSlot(); ?>


<?php if (isset($__componentOriginal431b364d377d769487a424b2915cb74feab0ffb8)): ?>
<?php $component = $__componentOriginal431b364d377d769487a424b2915cb74feab0ffb8; ?>
<?php unset($__componentOriginal431b364d377d769487a424b2915cb74feab0ffb8); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?><?php /**PATH /Users/richardjacobsen/development/rj-app/vendor/fruitcake/laravel-telescope-toolbar/src/../resources/views/collectors/redis.blade.php ENDPATH**/ ?>