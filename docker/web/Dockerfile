############
# Build base
############
FROM nginx:1.19.6 as base

# Update bash profile
COPY ./docker/.profile /root/.profile
COPY ./docker/.profile /root/.bashrc

# Copy configuration into container
COPY ./docker/web/dhparam.pem /etc/nginx/ssl/dhparam.pem





###################
# Build prod target
###################
FROM base as prod

# Copy prod config
COPY ./docker/web/nginx.conf /etc/nginx/conf.d/nginx.conf

# Copy the project files into the image for optimal production performance
COPY . /opt/project





##################
# Build dev target
##################
FROM base as dev
