<?php

describe('->isFeed', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('early feed', function () {
        beforeAll(function () {
            loadFixture('early-feed');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('feed');
            $header = $res->getHeader('wp-context-early-feed');
            expect($header[0])->toBe('true');
        });
    });

    describe('late feed', function () {
        beforeAll(function () {
            loadFixture('late-feed');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('feed');
            $header = $res->getHeader('wp-context-late-feed');
            expect($header[0])->toBe('true');
        });
    });
});
