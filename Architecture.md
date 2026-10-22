# Architecture Notes

This codebase prefers organization by user flow and use case rather than by technical concern.

## Core Principle

Keep things together that belong together.

The main goal is locality of behavior:

- related UI, state, workflows, and helpers should live near each other
- working on a feature should not require jumping across unrelated namespaces
- boundaries should reflect user-visible behavior and system capabilities

## Preferred Shape

Good boundaries usually look like:

- `AppChooser/`
- `Announcements/`
- `FeatureFlags/`
- `MainMenu/`
- `Patients/`
- `PatientDetails/`
- `Medication/`

Within a feature area, prefer small named objects such as:

- `Store`
- `Factory`
- `Resolver`
- `Builder`
- `Repository`

These names should describe a real role in the feature, not just wrap displaced code.

Name an object that carries out one use case after what it does, as an agent noun: `LicenseIssuer`, `LicenseRevoker`. Don't add a `Workflow` suffix.

## What To Avoid

Avoid creating or growing broad cross-cutting buckets such as:

- `Services/`
- `Managers/`
- `Helpers/`
- `Utilities/`

Avoid extracting code just to deduplicate incidental duplication. Shared abstractions should be introduced carefully and only when the shared concept is real.

## Code Clarity

- **Private methods.** Use them for small, readability-only decomposition — naming a step or hiding a few lines. Extract an injected collaborator when the private method grows complex enough to warrant its own test, or when it carries a responsibility distinct from the rest of the class.
- **Boolean chains.** Split chains that combine distinct preconditions — separate failure modes, separate validation rules — so each gets its own guard and its own error. Keep them together when they express a single composite check; if that check is hard to read, give it a name rather than fragmenting it across multiple `if` statements.
- **Early returns, not `else`.** Favor early returns whenever possible. A branch that returns or throws makes the trailing `else` redundant — it adds cognitive overhead without adding information.
- **No nested conditionals.** An `if` inside an `if` adds significant cognitive overhead. If you find yourself nesting, restructure with early returns or extract a collaborator instead.

## PHP Conventions

PHP's flexibility is a liability without discipline. Treat it as a strictly typed language.

- `declare(strict_types=1)` is non-negotiable. It prevents silent type coercion at function call boundaries.
- Enums exist for a reason. If a set of valid values is known at compile time, enumerate it. A caller should not be able to pass an invalid string.
- Raw arrays are an implementation detail, not a public API. When data crosses a boundary — function argument, return value, constructor parameter — it should be a typed object, not a plain array. Collections should validate their contents in the constructor.
- The service locator pattern resolves dependencies from a global registry at runtime. It hides coupling, defeats static analysis, and moves errors from load time to runtime. Use constructor injection instead.

## React / TypeScript Conventions

- TypeScript is the language. JavaScript is not. The type system is load-bearing — it catches the class of bug where an object silently coerces to `[object Object]` and breaks downstream behavior without throwing.
- Behavior belongs with the component. Event handlers, local state, and component-specific logic should live in or directly beside the component that owns them, not in a separate file justified by "separation of concerns." React's model is co-location; follow it.

## Refactor Heuristics

When changing code:

1. Move related files closer together first.
2. Extract small feature-local collaborators second.
3. Reevaluate whether any higher-level abstraction is actually necessary.

When touching a large file:

- prefer carving out focused, named collaborators
- keep those collaborators in the same feature directory by default
- stop extracting when the top-level object becomes a clear composer rather than a god object
