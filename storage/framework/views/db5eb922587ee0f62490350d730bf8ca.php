---
name: mcp-development
description: "Use this skill for Laravel MCP development only. Trigger when creating or editing MCP tools, resources, prompts, or servers in Laravel projects. Covers: artisan make:mcp-* generators, mcp:inspector, routes/ai.php, Tool/Resource/Prompt classes, schema validation, shouldRegister(), OAuth setup, URI templates, read-only attributes, and MCP debugging. Do not use for non-Laravel MCP projects or generic AI features without MCP."
license: MIT
metadata:
  author: laravel
---
<?php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
?>
# MCP Development

## Documentation First

**CRITICAL**: Always use ___SINGLE_BACKTICK___search-docs___SINGLE_BACKTICK___ BEFORE writing MCP code. The documentation is version-specific, comprehensive, and always up-to-date.

___BOOST_SNIPPET_0___

## Quick Reference

### Artisan Commands

Create MCP Primitives"
___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___bash
<?php echo e($assist->artisanCommand('make:mcp-tool ToolName')); ?>

<?php echo e($assist->artisanCommand('make:mcp-resource ResourceName')); ?>

<?php echo e($assist->artisanCommand('make:mcp-prompt PromptName')); ?>

<?php echo e($assist->artisanCommand('make:mcp-server ServerName')); ?>

___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___

### Basic Tool Implementation

___BOOST_SNIPPET_1___

### Basic Resource Implementation

___BOOST_SNIPPET_2___

### Response Methods

___BOOST_SNIPPET_3___

## Testing MCP Primitives

Test tools, resources, and prompts directly on their server:

___BOOST_SNIPPET_4___

### MCP Inspector

Test interactively using the inspector:

<!--Launch MCP Inspector-->
___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___bash
<?php echo e($assist->artisanCommand('mcp:inspector mcp/my-server')); ?>  # Web server
<?php echo e($assist->artisanCommand('mcp:inspector my-server')); ?>      # Local server
___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___

## Available Features

The following features exist—**use ___SINGLE_BACKTICK___search-docs___SINGLE_BACKTICK___ for implementation details**:

- **Tools**: ___SINGLE_BACKTICK___schema()___SINGLE_BACKTICK___, validation, annotations (___SINGLE_BACKTICK___#[IsReadOnly]___SINGLE_BACKTICK___, ___SINGLE_BACKTICK___#[IsDestructive]___SINGLE_BACKTICK___, etc.)
- **Resources**: URI templates (___SINGLE_BACKTICK___HasUriTemplate___SINGLE_BACKTICK___), Dynamic resources
- **Prompts**: Arguments, multi-message responses
- **All primitives**: Dependency injection, ___SINGLE_BACKTICK___shouldRegister()___SINGLE_BACKTICK___, validation
- **Responses**: Text, error, structured, streaming, metadata
- **Server registration**: Web routes, local routes, OAuth

## Critical Imports

___BOOST_SNIPPET_5___

## Common Pitfalls

- **Not using ___SINGLE_BACKTICK___search-docs___SINGLE_BACKTICK___ before implementation**
- Wrong imports: ___SINGLE_BACKTICK___Laravel\Mcp\Server\Request___SINGLE_BACKTICK___ (wrong) vs ___SINGLE_BACKTICK___Laravel\Mcp\Request___SINGLE_BACKTICK___ (correct)
- Forgetting ___SINGLE_BACKTICK___schema()___SINGLE_BACKTICK___ method for tools with parameters
- Missing required properties: ___SINGLE_BACKTICK___$description___SINGLE_BACKTICK___, ___SINGLE_BACKTICK___$uri___SINGLE_BACKTICK___, ___SINGLE_BACKTICK___$mimeType___SINGLE_BACKTICK___
- Wrong response pattern: ___SINGLE_BACKTICK___new Response()___SINGLE_BACKTICK___ instead of ___SINGLE_BACKTICK___Response::text()___SINGLE_BACKTICK___
- Running ___SINGLE_BACKTICK___mcp:start___SINGLE_BACKTICK___ command locally (hangs waiting for stdin)
<?php /**PATH C:\Herd\coaftcorig\storage\framework\views/a38d7be05ba5822b2fceca653e605ef5.blade.php ENDPATH**/ ?>