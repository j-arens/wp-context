<?php

describe('->isRobots', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('early robots', function () {
        beforeAll(function () {
            loadFixture('early-robots');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('robots.txt');
            $header = $res->getHeader('wp-context-early-robots');
            expect($header[0])->toBe('true');
        });
    });

    describe('late robots', function () {
        beforeAll(function () {
            loadFixture('late-robots');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('robots.txt');
            $header = $res->getHeader('wp-context-late-robots');
            expect($header[0])->toBe('true');
        });
    });
});
