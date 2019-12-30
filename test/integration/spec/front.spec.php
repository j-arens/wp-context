<?php

describe('->isFront', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('front', function () {
        beforeAll(function () {
            loadFixture('front');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('/');
            $header = $res->getHeader('wp-context-front');
            expect($header[0])->toBe('true');
        });
    });
});
