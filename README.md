# General Services

Aplicación web PHP (MVC) desplegada con Docker y Kubernetes (Helm).
PHP MVC web application deployed with Docker and Kubernetes (Helm).

---

## Español

### Requisitos previos

- [Docker](https://docs.docker.com/get-docker/)
- [kubectl](https://kubernetes.io/docs/tasks/tools/) configurado contra el cluster
- [Helm](https://helm.sh/docs/intro/install/) v3
- Namespace `general-services` creado en el cluster
- Acceso a Docker Hub con la cuenta `christopherguzman`

### Estructura del proyecto

```
.
├── deployment/          # Helm chart de la aplicación PHP
│   ├── Chart.yaml
│   ├── values.yaml
│   ├── 000-default.conf
│   └── templates/
│       ├── configmap.yaml
│       ├── deployment.yaml
│       ├── ingress.yaml
│       └── service.yaml
├── deployment/my-sql/   # Helm chart de MySQL
│   ├── Chart.yaml
│   ├── values.yaml
│   └── templates/
├── Dockerfile
└── ...
```

### Configuración

Los valores configurables se encuentran en `deployment/values.yaml`:

| Variable       | Valor por defecto                              | Descripción              |
|----------------|------------------------------------------------|--------------------------|
| `replicaCount` | `3`                                            | Réplicas de la app       |
| `DB_HOST`      | `mysql.general-services.svc.cluster.local`     | Host de MySQL en el cluster |
| `DB_NAME`      | `general-services`                             | Nombre de la base de datos |
| `DB_USER`      | `general-services`                             | Usuario de MySQL         |
| `DB_PASSWORD`  | `general-services`                             | Contraseña de MySQL      |
| `ingress.host` | `gs.iguzman.com.mx`                            | Dominio de la aplicación |

### Despliegue de MySQL (primera vez)

Si MySQL aún no está instalado en el cluster:

```bash
helm -n general-services install mysql deployment/my-sql
```

### Despliegue de la aplicación

#### 1. Construir la imagen Docker

```bash
docker build -t general-services .
```

#### 2. Publicar la imagen en Docker Hub

```bash
docker tag general-services christopherguzman/general-services:latest
docker push christopherguzman/general-services:latest
```

#### 3. Desplegar / Actualizar con Helm

**Primera instalación:**

```bash
helm -n general-services install general-services deployment
```

**Actualización (reinstalación completa):**

```bash
helm -n general-services uninstall general-services && \
helm -n general-services install general-services deployment
```

#### Comando completo (build + push + redeploy)

```bash
docker build -t general-services . && \
docker tag general-services christopherguzman/general-services:latest && \
docker push christopherguzman/general-services:latest && \
helm -n general-services uninstall general-services && \
helm -n general-services install general-services deployment
```

### Verificar el despliegue

```bash
kubectl -n general-services get pods
kubectl -n general-services get ingress
```

La aplicación quedará disponible en: `https://gs.iguzman.com.mx`

### Crear el archivo `.healthy` (requerido tras el despliegue)

Los pods usan un `startupProbe` y un `livenessProbe` que verifican la existencia del archivo `.healthy` dentro del volumen hostPath montado. Sin este archivo, los pods no pasarán a estado `Running`.

Tras el primer despliegue (o si el directorio aún no existe), crea el archivo usando una de las siguientes opciones:

**Opción A — directamente en el nodo:**

```bash
mkdir -p /shared-master/general-services/media
touch /shared-master/general-services/media/.healthy
```

**Opción B — vía kubectl (sin acceso directo al nodo):**

```bash
kubectl -n general-services run create-healthy \
  --image=busybox \
  --restart=Never \
  --rm \
  --overrides='{
    "spec": {
      "nodeSelector": {"nodeProjects": "real-clients"},
      "volumes": [{"name": "media", "hostPath": {"path": "/shared-master/general-services/media", "type": "DirectoryOrCreate"}}],
      "containers": [{"name": "create-healthy", "image": "busybox", "command": ["touch", "/media/.healthy"], "volumeMounts": [{"name": "media", "mountPath": "/media"}]}]
    }
  }' -- touch /media/.healthy
```

Este comando crea un pod temporal en el nodo correcto, monta el mismo hostPath, crea el archivo y se elimina solo.

> El namespace forma parte de la ruta. Si usas un namespace distinto a `general-services`, ajusta la ruta según corresponda.

---

## English

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [kubectl](https://kubernetes.io/docs/tasks/tools/) configured against the cluster
- [Helm](https://helm.sh/docs/intro/install/) v3
- Namespace `general-services` created in the cluster
- Docker Hub access with the `christopherguzman` account

### Project structure

```
.
├── deployment/          # PHP application Helm chart
│   ├── Chart.yaml
│   ├── values.yaml
│   ├── 000-default.conf
│   └── templates/
│       ├── configmap.yaml
│       ├── deployment.yaml
│       ├── ingress.yaml
│       └── service.yaml
├── deployment/my-sql/   # MySQL Helm chart
│   ├── Chart.yaml
│   ├── values.yaml
│   └── templates/
├── Dockerfile
└── ...
```

### Configuration

Configurable values are found in `deployment/values.yaml`:

| Variable       | Default value                                  | Description              |
|----------------|------------------------------------------------|--------------------------|
| `replicaCount` | `3`                                            | App replicas             |
| `DB_HOST`      | `mysql.general-services.svc.cluster.local`     | MySQL host in the cluster |
| `DB_NAME`      | `general-services`                             | Database name            |
| `DB_USER`      | `general-services`                             | MySQL user               |
| `DB_PASSWORD`  | `general-services`                             | MySQL password           |
| `ingress.host` | `gs.iguzman.com.mx`                            | Application domain       |

### Deploy MySQL (first time)

If MySQL is not yet installed in the cluster:

```bash
helm -n general-services install mysql deployment/my-sql
```

### Deploy the application

#### 1. Build the Docker image

```bash
docker build -t general-services .
```

#### 2. Push the image to Docker Hub

```bash
docker tag general-services christopherguzman/general-services:latest
docker push christopherguzman/general-services:latest
```

#### 3. Deploy / Update with Helm

**First install:**

```bash
helm -n general-services install general-services deployment
```

**Update (full reinstall):**

```bash
helm -n general-services uninstall general-services && \
helm -n general-services install general-services deployment
```

#### Full command (build + push + redeploy)

```bash
docker build -t general-services . && \
docker tag general-services christopherguzman/general-services:latest && \
docker push christopherguzman/general-services:latest && \
helm -n general-services uninstall general-services && \
helm -n general-services install general-services deployment
```

### Verify the deployment

```bash
kubectl -n general-services get pods
kubectl -n general-services get ingress
```

The application will be available at: `https://gs.iguzman.com.mx`

### Create the `.healthy` file (required after deployment)

Pods use a `startupProbe` and a `livenessProbe` that check for the existence of a `.healthy` file inside the mounted hostPath volume. Without this file, pods will not reach `Running` state.

After the first deployment (or if the directory does not yet exist), create the file using one of the following options:

**Option A — directly on the node:**

```bash
mkdir -p /shared-master/general-services/media
touch /shared-master/general-services/media/.healthy
```

**Option B — via kubectl (without direct node access):**

```bash
kubectl -n general-services run create-healthy \
  --image=busybox \
  --restart=Never \
  --rm \
  --overrides='{
    "spec": {
      "nodeSelector": {"nodeProjects": "real-clients"},
      "volumes": [{"name": "media", "hostPath": {"path": "/shared-master/general-services/media", "type": "DirectoryOrCreate"}}],
      "containers": [{"name": "create-healthy", "image": "busybox", "command": ["touch", "/media/.healthy"], "volumeMounts": [{"name": "media", "mountPath": "/media"}]}]
    }
  }' -- touch /media/.healthy
```

This command creates a temporary pod on the correct node, mounts the same hostPath, creates the file, and deletes itself.

> The namespace is part of the path. If you use a namespace other than `general-services`, adjust the path accordingly.
