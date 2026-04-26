---
name: mcp-development
description: "Use this skill for Laravel MCP development only. Trigger when creating or editing MCP tools, resources, prompts, or servers in Laravel projects. Covers: artisan make:mcp-* generators, mcp:inspector, routes/ai.php, Tool/Resource/Prompt classes, schema validation, shouldRegister(), OAuth setup, URI templates, read-only attributes, and MCP debugging. Do not use for non-Laravel MCP projects or generic AI features without MCP."
license: MIT
metadata:
  author: laravel
---
@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
# MCP Development

## Documentation

Use ___SINGLE_BACKTICK___search-docs___SINGLE_BACKTICK___ for detailed Laravel MCP patterns and documentation.

## Basic Usage

Register MCP servers in ___SINGLE_BACKTICK___routes/ai.php___SINGLE_BACKTICK___:

___BOOST_SNIPPET_0___

### Creating MCP Primitives

Create MCP tools, resources, prompts, and servers using artisan commands:

___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___bash
{{ $assist->artisanCommand('make:mcp-tool ToolName') }}        # Create a tool
{{ $assist->artisanCommand('make:mcp-resource ResourceName') }} # Create a resource
{{ $assist->artisanCommand('make:mcp-prompt PromptName') }}    # Create a prompt
{{ $assist->artisanCommand('make:mcp-server ServerName') }}    # Create a server
___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___

After creating primitives, register them in your server's ___SINGLE_BACKTICK___$tools___SINGLE_BACKTICK___, ___SINGLE_BACKTICK___$resources___SINGLE_BACKTICK___, or ___SINGLE_BACKTICK___$prompts___SINGLE_BACKTICK___ properties.

### Tools

___BOOST_SNIPPET_1___

### Registering Primitives in a Server

Each MCP server must explicitly declare the tools, resources, and prompts it exposes.

___BOOST_SNIPPET_2___

## Verification

1. Check ___SINGLE_BACKTICK___routes/ai.php___SINGLE_BACKTICK___ for proper registration
2. Test tool via MCP client

## Common Pitfalls

- Running ___SINGLE_BACKTICK___mcp:start___SINGLE_BACKTICK___ command (it hangs waiting for input)
- Using HTTPS locally with Node-based MCP clients
- Not using ___SINGLE_BACKTICK___search-docs___SINGLE_BACKTICK___ for the latest MCP documentation
- Not registering MCP server routes in ___SINGLE_BACKTICK___routes/ai.php___SINGLE_BACKTICK___
- Do not register ___SINGLE_BACKTICK___ai.php___SINGLE_BACKTICK___ in ___SINGLE_BACKTICK___bootstrap.php___SINGLE_BACKTICK___; it is registered automatically.
- OAuth registration supports custom URI schemes (e.g., ___SINGLE_BACKTICK___cursor://___SINGLE_BACKTICK___, ___SINGLE_BACKTICK___vscode://___SINGLE_BACKTICK___) for native desktop clients via ___SINGLE_BACKTICK___mcp.custom_schemes___SINGLE_BACKTICK___ config
