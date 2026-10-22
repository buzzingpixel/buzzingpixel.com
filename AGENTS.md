## General guidance on responses

- Be as concise as possible when responding. Don't be overly wordy. However, concise does not mean incomplete. When a question has real tradeoffs, or when the answer depends on something you can't verify, say so. Cutting a necessary caveat to save a line is not concision.
- Avoid technical jargon and confusing phrases whenever possible.
- Don't use filler. Be precise in your responses. The goal is not to make your responses bigger and longer, but shorter and easier to read.
- Don't be cheesy or repetitive.
- Be direct and to the point as much as possible.
- Answer the question first. Explanation second, and only if it's needed.
- For a yes/no question, start with yes or no.
- No preamble. Don't restate the question or announce what you're about to do.
- No closing summary of work the user can see in the diff or the tool output.
- Don't explain code you just wrote line by line. The user can read code.
- Don't hedge. Say what you think. If you don't know, say "I don't know."
- Don't list options you aren't recommending. Give the recommendation. The user can ask for additional options if needed.
- Never open with flattery ("Great question", "Good catch").
- Avoid: leverage, robust, seamless, comprehensive, holistic, "deep dive", "it's worth noting", "that said", "in essence", "the key insight", "you're absolutely right", "that's the whole story", "that's the crux of the issue", "smoking gun", "load bearing".
- Avoid the "It's not X, it's Y" construction and the three-item-with-dashes rhythm.
- When reporting a problem, state the problem and its cause. Skip the buildup.

## Code Comment Philosophy

A comment has to be earned. The default is no comment. Write one when the code cannot carry the information on its own.

**Comments explain why. Code already shows what and how.**

Earn a comment by explaining:

- a workaround or hack, and what it works around — link the bug report, ticket, or upstream issue if applicable
- complexity that looks removable but is not, and what breaks if someone simplifies it
- a business or domain constraint that the code can't state on its own ("the vendor's free tier caps this at three")
- a limitation imposed by a downstream system, third-party API, or browser
- a deliberate tradeoff — a slower or uglier approach chosen for a reason
- a reference: spec section, RFC, ticket, or documentation URL

Do not write:

- comments that restate the code, the function name, the types, or the signature
- narration of control flow, or a comment per step of an obvious sequence
- comments about the change itself: "changed to", "new", "previously", "now uses", "moved from", "as requested".
- comments addressed to the reviewer or to the person who asked for the change
- banner or divider comments, ASCII art, or section headers inside a file
- commented-out code. Delete it, unless it's for testing or is temporary in some other way and will be rolled back almost immediately.
- docblocks that only repeat what the type declarations already say. In PHP, add a docblock when it delineates or types something that can't be inferred — array shapes, generics, iterable value types.

Write comments that resist going stale:

- Comment the constraint, the decision, or the reason. Those outlive the implementation. Mechanics do not.
- Do not restate anything that lives in the code: names, values, counts, ordering, parameter lists. Each one is a copy that can drift out of sync.
- Attach a comment to the thing it explains, not to a line number or to code somewhere else in the file.
- When you change code, update or delete the comments that describe it.

Other rules:

- If a comment explains what code does, that's a naming problem. Rename the variable, rename the method, or extract a named collaborator instead.
- Keep comments to a sentence or two. Length does not add authority.
- Do not remove or rewrite an existing comment unless the code it describes changed. An unexplained comment may be recording something you can't see.
- Keep comments as close to the thing being commented on as possible. This helps reduce cognitive overhead.

## Architecture Rules

- Organize code by user flow and use case rather than by technical concern.
- Prefer co-location. UI, behavior, state, and feature-local helpers should live near the feature they serve.
- Optimize for locality of behavior. A developer should be able to understand or change a feature by reading a small, nearby cluster of files.
- Prefer file moves first and abstraction second.
- Prefer one small named object per use case over one large manager.
- Prefer role-based names such as `Store`, `Factory`, `Resolver`, `Builder`, and `Repository`.
- Name an object that carries out one use case as an agent noun for what it does (`LicenseIssuer`, `LicenseRevoker`), not with a `Workflow` suffix.
- Prefer file names that describe a role, feature slice, or user-visible responsibility rather than "an extension of X".
- Avoid vague suffixes such as `Actions`, `Content`, `Bindings`, `Operations`, or similar buckets when a more role-based name is available.
- Avoid vague buckets and vague names such as `Services`, `Managers`, `Helpers`, and `Utilities`.
- Keep extracted collaborators feature-local by default.
- When unsure between centralization and co-location, choose co-location.
- Do not normalize code into cross-cutting layers unless explicitly asked.
- When touching a large file, prefer extracting feature-local collaborators over extending the file further.
- Optimize for boundary clarity and maintainability over deduplication. Do not introduce shared abstractions for incidental duplication.

## PHP

- Declare `strict_types=1` at the top of every PHP file.
- Use PHP enums for any known set of values. Do not use magic strings or loose string constants when the valid options are known at compile time.
- Do not pass raw arrays as data transport between layers. Wrap them in purpose-built typed collection classes that validate and type-hint item types in the constructor.
- Prefer constructor injection. Do not use the service locator pattern — dependencies must be explicit, visible at the declaration site, and statically discoverable.
- Write code that is statically verifiable. Prefer patterns that phpstan can analyze without execution.

## React

- Write TypeScript. Do not use plain JavaScript. Rely on the type system to prevent silent coercion errors.
- Co-locate event handlers and component behavior with the component. Do not extract behavior into separate files in the name of "separation of concerns."

## Git

Run no git command that changes anything unless the user explicitly tells you to run it. That covers creating, switching, renaming, or deleting branches, checking out, staging, committing, stashing, tagging, fetching, pulling, pushing, merging, rebasing, and resetting. Reading git state (status, log, diff, show, blame) is fine.

When the user talks about how the work will be done, such as "let's do this in a branch", they're describing the plan, for the plan's documents. It isn't an instruction to run anything. The user will say plainly when they want a git command run.

The user may intentionally stage acceptable work before asking for further changes. Do not treat staged changes as a problem, and do not unstage files unless explicitly asked. When reporting repo state, distinguish staged and unstaged changes only if it matters for the task.

## Build Quality

The project must pass linting (phpcs, phpstan, eslint, tsc) with zero warnings.

## Review Standard

Before closing work, self-check against:

- `Architecture.md`
- `ReviewChecklist.md`
