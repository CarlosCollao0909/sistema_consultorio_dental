<?php 
$classes = [
    'error' => 'border border-rose-200 bg-rose-50 text-rose-700',
    'success' => 'border border-emerald-200 bg-emerald-50 text-emerald-700',
];

$icons = [
    'error' => 'fa-circle-exclamation',
    'success' => 'fa-circle-check',
];

foreach ($alerts as $type =>$messages):
    foreach ($messages as $message):
?>

<div class="mb-4 inline-flex w-full items-start gap-2 rounded-lg p-4 text-sm font-semibold <?php echo $classes[$type]; ?>">
    <i class="fa-solid <?php echo $icons[$type] ?? 'fa-circle-info'; ?> mt-0.5"></i>
    <span><?php echo $message; ?></span>
</div>

<?php
    endforeach;
endforeach;
?>