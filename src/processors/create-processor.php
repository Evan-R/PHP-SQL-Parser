<?php
/**
 * create-processor.php
 *
 * This file implements the processor for the CREATE statements.
 *
 * Copyright (c) 2010-2012, Justin Swanhart
 * with contributions by André Rothe <arothe@phosco.info, phosco@gmx.de>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
 * SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR
 * BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */
if (!defined('HAVE_CREATE_PROCESSOR')) {
    require_once(dirname(__FILE__) . '/abstract-processor.php');
    require_once(dirname(__FILE__) . '/../expression-types.php');

    /**
     * 
     * This class processes the CREATE statements.
     * 
     * @author arothe
     * 
     */
    class CreateProcessor extends AbstractProcessor {

        public function process($tokens) {
            $expr = array();
            foreach ($tokens as $token) {
                $trim = strtoupper(trim($token));

                if ($trim === "") {
                    continue;
                }

                switch ($trim) {

                case 'TEMPORARY':
                    $expr['expr_type'] = ExpressionType::TEMPORARY_TABLE;
                    $expr['exists'] = false;
                    break;

                case 'TABLE':
                    $expr['expr_type'] = ExpressionType::TABLE;
                    $expr['exists'] = false;
                    break;

                case 'IF NOT EXISTS':
                    $expr['exists'] = true;
                    break;

                default:
                    break;
                }
            }
            return $expr;
        }
    }
    define('HAVE_CREATE_PROCESSOR', 1);
}
