---
description: How to use Kilo Code CLI for AI-assisted engineering in DCMS SaaS
---

# Kilo Code Workflow

This workflow describes how to use the `@kilocode/cli` for specialized tasks within the DCMS SaaS project.

## 0. Configuration
Before using Kilo Code, ensure your API keys are configured in the `.env` file:
```env
OPENAI_API_KEY=sk-...
ANTHROPIC_API_KEY=sk-ant-...
```
After updating `.env`, restart any running Kilo Code process.

## 1. Architectural Planning
When designing a new complex feature or refactoring a core component:
```bash
kilocode --mode architect "Plan the implementation of [Feature Name] in the current workspace"
```

## 2. Debugging Complex Issues
When encountering a bug that spans multiple files or involves complex logic:
```bash
kilocode --mode debug "Help me find the root cause of [Error Message/Behavior] in the context of [Recent Changes]"
```

## 3. Automated Code Generation
To generate boiler plate or repetitive code patterns:
```bash
kilocode "Generate a new [Component/Model/Controller] following our existing patterns in [Reference File]"
```

## 4. Quick Q&A
For quick questions about the codebase without making changes:
```bash
kilocode --mode ask "Where is the [Logic/Component] handled for [Feature]?"
```

## Useful Shortcuts
- `Ctrl+Y`: YOLO mode (auto-approves operations)
- `Shift+Tab`: Cycle through agent modes
- `kilocode --continue`: Resume the last session
