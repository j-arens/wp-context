<?php

describe('->isGraphQL', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('graph ql', function () {
        beforeAll(function () {
            loadFixture('graphql');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->post('graphql', [
                'headers' => [
                    'content-type' => 'application/json',
                ],
                'body' => json_encode([
                    'query' => '
                        {
                            generalSettings {
                                url
                            }
                        }
                    '
                ]),
            ]);
            $header = $res->getHeader('wp-context-graphql');
            expect($header[0])->toBe('true');
        });
    });
});
