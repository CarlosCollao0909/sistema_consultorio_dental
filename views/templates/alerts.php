<?php 
$classes = [
    'error' => 'bg-red-600 text-white border-l-4 border-red-800',
    'success' => 'bg-green-600 text-white border-l-4 border-green-800',
];

foreach ($alerts as $type =>$messages):
    foreach ($messages as $message):
?>

<div class="p-4 rounded-lg mb-4 font-bold text-sm <?php echo $classes[$type]; ?>">
    <?php echo $message; ?>
</div>

<?php
    endforeach;
endforeach;
?>