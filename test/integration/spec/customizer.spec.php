<?php

describe('->isCustomizer', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('early customizer', function () {
        beforeAll(function () {
            loadFixture('early-customizer');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('wp-admin/customize.php', [
                'cookies' => login($this->client, 'admin'),
            ]);
            $header = $res->getHeader('wp-context-early-customizer');
            expect($header[0])->toBe('true');
        });
    });

    describe('late customizer', function () {
        beforeAll(function () {
            loadFixture('late-customizer');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('wp-admin/customize.php', [
                'cookies' => login($this->client, 'admin'),
            ]);
            $header = $res->getHeader('wp-context-late-customizer');
            expect($header[0])->toBe('true');
        });
    });
});
