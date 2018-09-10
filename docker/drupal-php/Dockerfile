ARG PHP_TAG

FROM wodby/drupal-php:${PHP_TAG}

USER root

RUN set -ex; \
    \
    # Drush ^9.0 version installation.
    su-exec wodby composer global require drush/drush:^9.0; \
    su-exec wodby drush cc drush
