<?php
/**
 * DorpFlow ERP - Main Layout Footer template
 */
$user = Auth::user();
?>
<?php if ($user): ?>
    </div> <!-- Close content shell -->
</div> <!-- Close wrapper -->
<?php endif; ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet Maps JS -->
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Custom application global JS -->
<script src="<?php echo APP_URL; ?>/public/js/app.js"></script>

</body>
</html>
