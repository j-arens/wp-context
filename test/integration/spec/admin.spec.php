<?php

describe('->isAdmin', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('dashboard page', function () {
        beforeAll(function () {
            loadFixture('admin-dashboard');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('wp-admin/index.php');
            $header = $res->getHeader('wp-context-admin-dashboard');
            expect($header[0])->toBe('true');
        });
    });

    describe('admin ajax', function () {
        beforeAll(function () {
            loadFixture('admin-ajax');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->post('wp-admin/admin-ajax.php', [
                'form_params' => [
                    'action' => 'wp_context',
                ],
            ]);
            $header = $res->getHeader('wp-context-admin-ajax');
            expect($header[0])->toBe('true');
        });
    });

    describe('xml rpc', function () {
        beforeAll(function () {
            loadFixture('admin-xmlrpc');
        });

        it('should return the correct response on xmlrpc requests', function () {
            $res = $this->client->get('xmlrpc.php');
            $header = $res->getHeader('wp-context-admin-xmlrpc');
            expect($header[0])->toBe('true');
        });
    });
});
