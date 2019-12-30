<?php

describe('->isXmlRpc', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('xml rpc', function () {
        beforeAll(function () {
            loadFixture('xmlrpc');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('xmlrpc.php');
            $header = $res->getHeader('wp-context-xmlrpc');
            expect($header[0])->toBe('true');
        });
    });
});
