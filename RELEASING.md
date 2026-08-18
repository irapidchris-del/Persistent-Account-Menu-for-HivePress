# Releasing Persistent Account Menu for HivePress

## The permanent download link

```
https://github.com/irapidchris-del/Persistent-Account-Menu-for-HivePress/releases/latest/download/persistent-account-menu-for-hivepress.zip
```

This link is published on the HivePress community forum and must keep working for
every future release without being edited.

**The release asset must therefore be named exactly
`persistent-account-menu-for-hivepress.zip`, with no version number in the
filename, on every single release.** GitHub resolves
`/releases/latest/download/{filename}` by looking for that exact filename in the
newest release, so a versioned asset makes the permanent link 404.

Also required for the link to resolve: the release must be published (not a
draft) and not marked pre-release, the repository must be public, and there must
be exactly one `.zip` asset on the release.

## How to release

`.github/workflows/release.yml` builds and attaches the asset, so the naming
decision is fixed in the repo rather than left to whoever is releasing.

1. **Push the source first.** The workflow builds from `$GITHUB_SHA`, so
   publishing before the code is pushed ships the OLD code under the NEW version
   number, and the updater will then consider it up to date.
2. Confirm the pushed version headers by re-reading `readme.txt` and the main
   file from `raw.githubusercontent.com`.
3. Trigger the workflow: `workflow_dispatch` with `tag: vX.Y.Z` and the
   changelog markdown as `notes`, or publish a release from the GitHub UI (the
   workflow also fires on `release: published` and re-uploads with `--clobber`).
4. Verify with a GET, never a HEAD: `curl -sL -o /dev/null -w "%{http_code}"`
   against the permanent link. A HEAD returns a misleading 401 because GitHub
   redirects to a signed object-store URL that rejects HEAD.

Never re-dispatch the workflow with an old tag to edit an old release: it
force-moves the tag to current HEAD and would publish new code under the old
label.

## The build allowlist

The workflow copies **named paths only**. A new top-level file is silently
missing from every release while working perfectly in local testing, so update
the `cp` lines in the same commit as any new top-level path. Current list:

```
persistent-account-menu-for-hivepress.php uninstall.php readme.txt README.md LICENSE
languages/
```

`phpcs.xml`, `RELEASING.md` and `.github/` are deliberately omitted from the
release. `tools/package.ps1` reads the `cp` lines out of the workflow above and
ships exactly those paths, so a zip built locally carries the same files as the
published one. It prints `Source: release.yml allowlist` to say so.

## Before building

1. Bump the version in the plugin header, `readme.txt` `Stable tag`, and add a
   changelog entry.
2. Regenerate the POT if strings changed:
   `wp i18n make-pot . languages/persistent-account-menu-for-hivepress.pot --domain=persistent-account-menu-for-hivepress`
3. `analyse.ps1` must print `RESULT: clean`.
4. Version numbers only ever go up. A lower number is invisible to WordPress's
   `version_compare`, and GitHub's `/releases/latest` sorts by creation date, so
   a lower-numbered release published later permanently blocks updates.
