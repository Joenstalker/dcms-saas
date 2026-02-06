<?php
    use App\Services\TenantBrandingService;

    $brandingService = app(TenantBrandingService::class);
    $customThemes = $brandingService->getActiveThemes();

    if (!function_exists('hexToHsl')) {
        function hexToHsl($hex) {
            $hex = str_replace('#', '', $hex);
            $len = strlen($hex);
            $r = $g = $b = 0;
            if ($len === 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1)) / 255;
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1)) / 255;
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1)) / 255;
            } else {
                $r = hexdec(substr($hex, 0, 2)) / 255;
                $g = hexdec(substr($hex, 2, 2)) / 255;
                $b = hexdec(substr($hex, 4, 2)) / 255;
            }
            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $l = ($max + $min) / 2;
            if ($max === $min) {
                return ['h' => 0, 's' => 0, 'l' => round($l * 100, 1)];
            }
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            switch ($max) {
                case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                case $g: $h = ($b - $r) / $d + 2; break;
                default: $h = ($r - $g) / $d + 4; break;
            }
            $h /= 6;
            return ['h' => round($h * 360), 's' => round($s * 100, 1), 'l' => round($l * 100, 1)];
        }
    }

    if (!function_exists('getLuminance')) {
        function getLuminance($hex) {
            $hex = str_replace('#', '', $hex);
            $len = strlen($hex);
            if ($len === 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1)) / 255;
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1)) / 255;
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1)) / 255;
            } else {
                $r = hexdec(substr($hex, 0, 2)) / 255;
                $g = hexdec(substr($hex, 2, 2)) / 255;
                $b = hexdec(substr($hex, 4, 2)) / 255;
            }
            return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
        }
    }
?>

<style id="custom-theme-styles">
    <?php $__currentLoopData = $customThemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        [data-theme="custom-<?php echo e($theme->slug); ?>"] {
            <?php $__currentLoopData = $theme->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $hex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $hsl = hexToHsl($hex);
                    $varName = match($name) {
                        'primary' => 'p',
                        'secondary' => 's',
                        'accent' => 'a',
                        'neutral' => 'n',
                        'base-100' => 'b1',
                        'base-200' => 'b2',
                        'base-300' => 'b3',
                        default => $name
                    };
                    $varContent = match($name) {
                        'primary' => 'pc',
                        'secondary' => 'sc',
                        'accent' => 'ac',
                        'neutral' => 'nc',
                        'info' => 'infoc',
                        'success' => 'successc',
                        'warning' => 'warningc',
                        'error' => 'errorc',
                        default => null
                    };
                ?>
                --<?php echo e($varName); ?>: <?php echo e($hsl['h']); ?> <?php echo e($hsl['s']); ?>% <?php echo e($hsl['l']); ?>%;
                <?php if($varContent): ?>
                    --<?php echo e($varContent); ?>: <?php echo e(getLuminance($hex) > 0.5 ? '0 0% 0%' : '0 0% 100%'); ?>;
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            --bc: <?php echo e(getLuminance($theme->colors['base-100']) > 0.5 ? '215 28% 17%' : '0 0% 100%'); ?>;
        }
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</style>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/components/custom-theme-styles.blade.php ENDPATH**/ ?>