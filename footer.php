</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <?php if (is_active_sidebar('footer-widgets')) : ?>
                <?php dynamic_sidebar('footer-widgets'); ?>
            <?php endif; ?>
        </div>
        
        <div class="footer-bottom text-center mt-2">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'distributor-connect'); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>