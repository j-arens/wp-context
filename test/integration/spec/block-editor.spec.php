<?php

describe('->isBlockEditor', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('block editor', function () {
        beforeAll(function () {
            loadFixture('block-editor');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('wp-admin/post.php?post=1&action=edit', [
                'cookies' => login($this->client, 'admin'),
            ]);
            $header = $res->getHeader('wp-context-block-editor');
            expect($header[0])->toBe('true');
        });
    });
});
