@echo off
echo Checking and cleaning merged remote branches...
git fetch --all
echo Checking feature/service-creation-improvements...
git log origin/main..origin/feature/service-creation-improvements > nul
IF %ERRORLEVEL% EQU 0 (
    echo Branch feature/service-creation-improvements is fully merged. Deleting...
    git push origin --delete feature/service-creation-improvements
) ELSE (
    echo Branch feature/service-creation-improvements has unmerged commits. Keeping.
)
echo Checking feature/service-editing-tabs...
git log origin/main..origin/feature/service-editing-tabs > nul
IF %ERRORLEVEL% EQU 0 (
    echo Branch feature/service-editing-tabs is fully merged. Deleting...
    git push origin --delete feature/service-editing-tabs
) ELSE (
    echo Branch feature/service-editing-tabs has unmerged commits. Keeping.
)
echo Checking fix-twig-render-error...
git log origin/main..origin/fix-twig-render-error > nul
IF %ERRORLEVEL% EQU 0 (
    echo Branch fix-twig-render-error is fully merged. Deleting...
    git push origin --delete fix-twig-render-error
) ELSE (
    echo Branch fix-twig-render-error has unmerged commits. Keeping.
)
echo Checking fix/fichar-button...
git log origin/main..origin/fix/fichar-button > nul
IF %ERRORLEVEL% EQU 0 (
    echo Branch fix/fichar-button is fully merged. Deleting...
    git push origin --delete fix/fichar-button
) ELSE (
    echo Branch fix/fichar-button has unmerged commits. Keeping.
)
echo Checking refactor/move-header-to-sidebar...
git log origin/main..origin/refactor/move-header-to-sidebar > nul
IF %ERRORLEVEL% EQU 0 (
    echo Branch refactor/move-header-to-sidebar is fully merged. Deleting...
    git push origin --delete refactor/move-header-to-sidebar
) ELSE (
    echo Branch refactor/move-header-to-sidebar has unmerged commits. Keeping.
)
git remote prune origin
echo Cleanup complete.