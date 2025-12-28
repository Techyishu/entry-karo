# Docker Migration Fix

## Problem
The application was failing with a `500 Server Error` and logs showed:
`SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "sessions" does not exist`

## Cause
When deploying with Docker on Render, the database is not available during the *build* phase (`docker build`). Therefore, standard migration commands cannot run during the build. Since no mechanism was running them at *startup* (runtime), the database remained empty, causing the application to crash when trying to access the session table.

## Solution implemented
1.  **Created `docker-entrypoint.sh`**: A specific startup script for the Docker container.
    *   Runs `php artisan migrate --force` to ensure the database is up-to-date.
    *   Runs `php artisan storage:link` to ensure file storage works.
    *   Runs `php artisan config:cache` (and route/view cache) for performance.
    *   Optionally runs the `SuperAdminSeeder` if `CREATE_ADMIN=true`.
    *   Finally, starts Apache (`apache2-foreground`).

2.  **Updated `Dockerfile`**:
    *   Added instructions to `COPY` the `docker-entrypoint.sh` into the image.
    *   Made the script executable.
    *   Defined `ENTRYPOINT ["docker-entrypoint.sh"]` to ensure this script runs every time the container starts.

## Next Steps for You
1.  **Commit and Push**: Ensure these changes are pushed to your git repository.
    ```bash
    git add Dockerfile docker-entrypoint.sh
    git commit -m "Add docker entrypoint to run migrations at runtime"
    git push origin main
    ```
2.  **Wait for Redeploy**: Render will automatically detect the push and rebuild the Docker image.
3.  **Monitor Logs**: You should see "🚀 Starting container startup script..." and "🗄️ Running database migrations..." in the Render logs.
4.  **Verify**: The app should load correctly after the deployment finishes.
