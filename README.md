# 麻雀成績管理サイト（Laravel + React/Next.js / AWS）  
※開発中

家族・友人間で利用する[麻雀成績管理サービス](https://github.com/fuya-kondo/aisai_m_league) を、既存版（PHP/JS）からフルリプレイスするプロジェクトです。  
**ドメイン設計・スケーラブルな構成**を重視して開発しています。

---

## 技術スタック

### バックエンド
- **Laravel 12**
- PHP 8.4
- PostgreSQL
- Redis（Session / Cache）

### フロントエンド（予定）
- React
- Vite
- API 分離構成（SPA 前提）

### インフラ・開発環境
- Docker / docker-compose
- nginx + php-fpm
- PostgreSQL
- Redis
- AWS を前提とした構成設計
  - ALB
  - ECS (Fargate)
  - RDS (PostgreSQL)
  - ElastiCache (Redis)

---

## AWS Architecture

本番環境は **スケーラビリティ・可用性・運用性**を考慮し、  
AWS マネージドサービスを中心に構成しています。

### 全体構成図

```mermaid
flowchart TB
    Internet --> Route53
    Route53 --> ALB

    subgraph VPC["VPC (Private Subnet)"]
        ALB --> ECS_API

        subgraph ECS_API["ECS Fargate (API)"]
            NGINX["nginx"]
            PHP["php-fpm / Laravel"]
            NGINX --> PHP
        end

        PHP --> RDS[(RDS PostgreSQL)]
        PHP --> REDIS[(ElastiCache Redis)]

        PHP --> ECS_WORKER

        subgraph ECS_WORKER["ECS Fargate (Worker)"]
            WORKER["Queue / Async Jobs"]
        end
    end

    Browser --> CloudFront
    CloudFront --> S3["S3 (React SPA)"]
    CloudFront --> ALB





