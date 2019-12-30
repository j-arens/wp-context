<?php

describe('->isWpCli', function () {
    describe('wp cli', function () {
        beforeAll(function () {
            loadFixture('wpcli');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $output = shell_exec('/usr/local/bin/wp --allow-root --path=/var/www/html plugin list');
            expect($output)->toBe('wp-context-wp-cli');
        });
    });
});
